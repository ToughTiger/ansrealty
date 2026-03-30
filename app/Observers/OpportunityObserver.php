<?php

namespace App\Observers;

use App\Models\Opportunity;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class OpportunityObserver
{
    /**
     * Handle the Opportunity "created" event.
     */
    public function created(Opportunity $opportunity): void
    {
        // Send notification to assigned agent
        try {
            if ($opportunity->assignedAgent) {
                $opportunity->assignedAgent->notify(
                    new \App\Notifications\OpportunityCreated($opportunity)
                );
                Log::info("Opportunity created notification sent for Opportunity #{$opportunity->id}");
            }
        } catch (\Exception $e) {
            Log::error("Opportunity notification failed: " . $e->getMessage());
        }
    }

    /**
     * Handle the Opportunity "updated" event.
     */
    public function updated(Opportunity $opportunity): void
    {
        // Check if stage changed
        if ($opportunity->isDirty('opportunity_stage_id')) {
            try {
                $newStage = $opportunity->opportunityStage;
                
                // Define next action based on stage
                $taskConfig = $this->getTaskForStage($newStage->name);
                
                if ($taskConfig) {
                    Task::create([
                        'taskable_type' => Opportunity::class,
                        'taskable_id' => $opportunity->id,
                        'assigned_to' => $opportunity->assigned_to,
                        'type' => $taskConfig['type'],
                        'title' => $taskConfig['title'],
                        'description' => $taskConfig['description'],
                        'due_date' => now()->add($taskConfig['due_in']),
                        'priority' => $taskConfig['priority'],
                        'status' => 'Pending',
                    ]);

                    Log::info("Auto-task created for Opportunity #{$opportunity->id} stage: {$newStage->name}");
                }

                // Update lead's last activity
                if ($opportunity->lead) {
                    $opportunity->lead->update(['last_activity_at' => now()]);
                    $opportunity->lead->increment('interaction_count');
                }
            } catch (\Exception $e) {
                Log::error("Opportunity task creation failed: " . $e->getMessage());
            }
        }
    }

    /**
     * Get task configuration based on opportunity stage
     */
    protected function getTaskForStage(string $stageName): ?array
    {
        $tasks = [
            'Qualification' => [
                'type' => 'Call',
                'title' => 'Qualify Opportunity',
                'description' => 'Understand customer requirements, budget, and timeline. Qualify if this is a genuine opportunity.',
                'due_in' => '2 hours',
                'priority' => 'High',
            ],
            'Needs Analysis' => [
                'type' => 'Meeting',
                'title' => 'Schedule Needs Analysis Meeting',
                'description' => 'Deep dive into customer requirements. Discuss property preferences, location, amenities.',
                'due_in' => '1 day',
                'priority' => 'High',
            ],
            'Proposal' => [
                'type' => 'Email',
                'title' => 'Send Property Proposal',
                'description' => 'Prepare and send detailed proposal with shortlisted properties, pricing, payment plans.',
                'due_in' => '2 days',
                'priority' => 'Medium',
            ],
            'Negotiation' => [
                'type' => 'Call',
                'title' => 'Negotiate Terms',
                'description' => 'Discuss pricing, payment terms, discounts. Work towards agreement.',
                'due_in' => '1 day',
                'priority' => 'High',
            ],
            'Verbal Commitment' => [
                'type' => 'Meeting',
                'title' => 'Agreement Documentation',
                'description' => 'Prepare agreement documents. Schedule signing meeting.',
                'due_in' => '3 days',
                'priority' => 'High',
            ],
            'Agreement Sent' => [
                'type' => 'Call',
                'title' => 'Follow-up on Agreement',
                'description' => 'Follow-up to ensure agreement is reviewed and signed.',
                'due_in' => '2 days',
                'priority' => 'Medium',
            ],
            'Closed Won' => [
                'type' => 'Email',
                'title' => 'Post-Sale Follow-up',
                'description' => 'Thank customer. Ensure smooth transition to booking/registration process.',
                'due_in' => '1 day',
                'priority' => 'Low',
            ],
        ];

        return $tasks[$stageName] ?? null;
    }
}
