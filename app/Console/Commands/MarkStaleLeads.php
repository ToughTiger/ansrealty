<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Models\User;
use App\Notifications\StaleLeadAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MarkStaleLeads extends Command
{
    protected $signature = 'leads:mark-stale {--days=14 : Number of days to consider lead as stale}';

    protected $description = 'Mark leads as stale if no activity for specified days and send alerts';

    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        $this->info("Marking leads as stale (no activity since {$cutoffDate->format('Y-m-d H:i:s')})...");

        // Find leads that should be marked as stale
        $leads = Lead::whereNull('converted_at')
            ->whereNull('lost_at')
            ->where('is_stale', false)
            ->where(function($query) use ($cutoffDate) {
                $query->where('last_activity_at', '<', $cutoffDate)
                    ->orWhereNull('last_activity_at');
            })
            ->get();

        $count = $leads->count();

        if ($count === 0) {
            $this->info('No leads to mark as stale.');
            return 0;
        }

        $this->info("Found {$count} leads to mark as stale.");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $markedCount = 0;
        $notificationsSent = 0;

        foreach ($leads as $lead) {
            // Mark as stale
            $lead->is_stale = true;
            $lead->marked_stale_at = now();
            $lead->saveQuietly();

            $markedCount++;

            // Send notification to assigned agent
            if ($lead->assigned_to) {
                try {
                    $agent = User::find($lead->assigned_to);
                    if ($agent) {
                        $agent->notify(new StaleLeadAlert($lead));
                        $notificationsSent++;
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to send stale lead notification for lead {$lead->id}: " . $e->getMessage());
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Marked {$markedCount} leads as stale");
        $this->info("📧 Sent {$notificationsSent} notifications to agents");

        // Log to system
        Log::info("Stale leads marked: {$markedCount}, Notifications sent: {$notificationsSent}");

        return 0;
    }
}
