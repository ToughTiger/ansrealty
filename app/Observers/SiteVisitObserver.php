<?php

namespace App\Observers;

use App\Models\SiteVisit;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class SiteVisitObserver
{
    /**
     * Handle the SiteVisit "updated" event.
     */
    public function updated(SiteVisit $siteVisit): void
    {
        // Check if status changed to "Completed"
        if ($siteVisit->isDirty('status') && $siteVisit->status === 'Completed') {
            try {
                $propertyName = 'property';
                if ($siteVisit->visitable && isset($siteVisit->visitable->property)) {
                    $propertyName = $siteVisit->visitable->property->name;
                }
                
                // Create follow-up task
                $task = Task::create([
                    'taskable_type' => get_class($siteVisit->visitable),
                    'taskable_id' => $siteVisit->visitable_id,
                    'assigned_to' => $siteVisit->assigned_to,
                    'type' => 'Call',
                    'title' => 'Follow-up After Site Visit',
                    'description' => "Follow-up call after site visit to {$propertyName}. Gather feedback and discuss next steps.",
                    'due_date' => now()->addDay(), // Due tomorrow
                    'priority' => 'High',
                    'status' => 'Pending',
                ]);

                Log::info("Follow-up task created after site visit #{$siteVisit->id} completion");

                // Update lead's last activity
                if ($siteVisit->visitable_type === 'App\Models\Lead') {
                    $siteVisit->visitable->update([
                        'last_activity_at' => now(),
                    ]);
                    $siteVisit->visitable->increment('interaction_count');
                }
            } catch (\Exception $e) {
                Log::error("Site visit task creation failed: " . $e->getMessage());
            }
        }
    }
}
