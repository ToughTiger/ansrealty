<?php

namespace App\Observers;

use App\Models\Lead;
use App\Models\Task;
use App\Services\LeadAssignmentService;
use Illuminate\Support\Facades\Log;

class LeadObserver
{
    protected $assignmentService;

    public function __construct(LeadAssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    /**
     * Handle the Lead "created" event.
     */
    public function created(Lead $lead): void
    {
        // Auto-assign if not already assigned
        if (!$lead->assigned_to) {
            try {
                $assignedUser = $this->assignmentService->autoAssign($lead);
                
                if ($assignedUser) {
                    $lead->update(['assigned_to' => $assignedUser->id]);
                    Log::info("Lead #{$lead->id} auto-assigned to {$assignedUser->name}");
                    
                    // Send email notification to assigned agent
                    $assignedUser->notify(new \App\Notifications\LeadAssigned($lead));
                }
            } catch (\Exception $e) {
                Log::error("Lead auto-assignment failed: " . $e->getMessage());
            }
        } elseif ($lead->assigned_to) {
            // If manually assigned, still send notification
            try {
                $assignedUser = \App\Models\User::find($lead->assigned_to);
                if ($assignedUser) {
                    $assignedUser->notify(new \App\Notifications\LeadAssigned($lead));
                }
            } catch (\Exception $e) {
                Log::error("Lead notification failed: " . $e->getMessage());
            }
        }

        // Set initial last_activity_at
        $lead->update(['last_activity_at' => now()]);

        // Auto-create initial contact task
        try {
            Task::create([
                'taskable_type' => Lead::class,
                'taskable_id' => $lead->id,
                'assigned_to' => $lead->assigned_to,
                'type' => 'Call',
                'title' => 'Initial Contact - New Lead',
                'description' => "Call {$lead->full_name} to discuss their property requirements. Budget: ₹" . number_format($lead->budget_min ?? 0) . " - ₹" . number_format($lead->budget_max ?? 0),
                'due_date' => now()->addHour(), // Due in 1 hour
                'priority' => $lead->priority === 'Hot' ? 'High' : 'Medium',
                'status' => 'Pending',
            ]);
            
            Log::info("Initial contact task created for Lead #{$lead->id}");
        } catch (\Exception $e) {
            Log::error("Task creation failed: " . $e->getMessage());
        }
    }

    /**
     * Handle the Lead "updated" event.
     */
    public function updated(Lead $lead): void
    {
        // Update last_activity_at on any change
        if ($lead->isDirty() && !$lead->isDirty('last_activity_at')) {
            $lead->last_activity_at = now();
            $lead->saveQuietly(); // Prevent infinite loop
        }

        // If lead was marked as stale but now updated, remove stale flag
        if ($lead->is_stale && $lead->wasChanged() && !$lead->isDirty('is_stale')) {
            $lead->is_stale = false;
            $lead->marked_stale_at = null;
            $lead->saveQuietly();
        }

        // Track interactions and notify on status change
        if ($lead->isDirty('lead_status_id')) {
            $lead->increment('interaction_count');
            
            // Send status change notification
            try {
                if ($lead->assignedAgent) {
                    $oldStatus = $lead->getOriginal('lead_status_id') 
                        ? \App\Models\LeadStatus::find($lead->getOriginal('lead_status_id'))
                        : null;
                    $newStatus = $lead->leadStatus;
                    
                    $lead->assignedAgent->notify(
                        new \App\Notifications\LeadStatusChanged($lead, $oldStatus, $newStatus)
                    );
                }
            } catch (\Exception $e) {
                Log::error("Status change notification failed: " . $e->getMessage());
            }
            
            // Auto-update to "Qualified" after 2+ interactions
            if ($lead->interaction_count >= 2) {
                $qualifiedStatus = \App\Models\LeadStatus::where('name', 'Qualified')->first();
                if ($qualifiedStatus && $lead->lead_status_id !== $qualifiedStatus->id) {
                    $lead->lead_status_id = $qualifiedStatus->id;
                    $lead->qualified_at = now();
                    $lead->saveQuietly();
                    
                    Log::info("Lead #{$lead->id} auto-qualified after {$lead->interaction_count} interactions");
                }
            }
        }
    }
}
