<?php

namespace App\Console\Commands;

use App\Models\Opportunity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoCloseLostOpportunities extends Command
{
    protected $signature = 'opportunities:auto-close {--days=30 : Number of days after which to auto-close lost opportunities}';

    protected $description = 'Automatically close opportunities that have been in lost/inactive state for specified days';

    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        $this->info("Auto-closing opportunities inactive since {$cutoffDate->format('Y-m-d H:i:s')}...");

        // Find opportunities in "Lost" or inactive stages
        $opportunities = Opportunity::whereHas('opportunityStage', function($query) {
                $query->where('name', 'LIKE', '%Lost%')
                    ->orWhere('name', 'LIKE', '%Rejected%')
                    ->orWhere('name', 'LIKE', '%Cancelled%');
            })
            ->whereNull('closed_at')
            ->where('updated_at', '<', $cutoffDate)
            ->get();

        $count = $opportunities->count();

        if ($count === 0) {
            $this->info('No opportunities to auto-close.');
            return 0;
        }

        $this->info("Found {$count} opportunities to auto-close.");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $closedCount = 0;

        foreach ($opportunities as $opportunity) {
            $opportunity->closed_at = now();
            $opportunity->notes = ($opportunity->notes ?? '') . "\n\n[AUTO-CLOSED] Automatically closed after {$days} days of inactivity on " . now()->format('Y-m-d H:i:s');
            $opportunity->saveQuietly();

            $closedCount++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Auto-closed {$closedCount} opportunities");

        // Log to system
        Log::info("Auto-closed opportunities: {$closedCount}");

        return 0;
    }
}
