<?php
/**
 * Script to create Filament Widgets directory and widget files
 */

$baseDir = __DIR__ . '/app/Filament/Widgets';

// Create Widgets directory if it doesn't exist
if (!is_dir($baseDir)) {
    mkdir($baseDir, 0755, true);
    echo "✅ Created Widgets directory\n";
} else {
    echo "✅ Widgets directory exists\n";
}

// Widget files content
$widgets = [
    'LeadStatsOverview.php' => <<<'PHP'
<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LeadStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        try {
            $totalLeads = Lead::count();
            $hotLeads = Lead::where('priority', 'Hot')->count();
            $warmLeads = Lead::where('priority', 'Warm')->count();
            $coldLeads = Lead::where('priority', 'Cold')->count();
            
            $newLeadsThisMonth = Lead::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            
            $leadsWithOpportunities = Lead::has('opportunities')->count();
            $conversionRate = $totalLeads > 0 ? round(($leadsWithOpportunities / $totalLeads) * 100, 1) : 0;
            
            $lastMonthLeads = Lead::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count();
            
            $trend = $lastMonthLeads > 0 
                ? round((($newLeadsThisMonth - $lastMonthLeads) / $lastMonthLeads) * 100, 1) 
                : 0;
            
            return [
                Stat::make('Total Leads', $totalLeads)
                    ->description('All time leads')
                    ->descriptionIcon('heroicon-o-users')
                    ->color('primary')
                    ->chart([7, 12, 15, 18, 22, 25, $totalLeads]),
                
                Stat::make('Hot Leads', $hotLeads)
                    ->description('High priority leads')
                    ->descriptionIcon('heroicon-o-fire')
                    ->color('danger'),
                
                Stat::make('Warm Leads', $warmLeads)
                    ->description('Medium priority leads')
                    ->descriptionIcon('heroicon-o-sun')
                    ->color('warning'),
                
                Stat::make('Cold Leads', $coldLeads)
                    ->description('Low priority leads')
                    ->descriptionIcon('heroicon-o-beaker')
                    ->color('info'),
                
                Stat::make('New This Month', $newLeadsThisMonth)
                    ->description($trend >= 0 ? "{$trend}% increase" : "{$trend}% decrease")
                    ->descriptionIcon($trend >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                    ->color($trend >= 0 ? 'success' : 'danger'),
                
                Stat::make('Conversion Rate', "{$conversionRate}%")
                    ->description("{$leadsWithOpportunities} leads converted")
                    ->descriptionIcon('heroicon-o-chart-bar')
                    ->color($conversionRate >= 50 ? 'success' : ($conversionRate >= 25 ? 'warning' : 'danger')),
            ];
        } catch (\Exception $e) {
            return [
                Stat::make('Total Leads', '0')
                    ->description('No data available')
                    ->descriptionIcon('heroicon-o-information-circle')
                    ->color('secondary'),
            ];
        }
    }
}
PHP
    ,

    'OpportunityPipelineWidget.php' => <<<'PHP'
<?php

namespace App\Filament\Widgets;

use App\Models\Opportunity;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OpportunityPipelineWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected function getStats(): array
    {
        try {
            $totalOpportunities = Opportunity::where('close_status', 'open')->count();
            
            $pipelineValue = Opportunity::where('close_status', 'open')
                ->sum('expected_value');
            
            $weightedPipeline = Opportunity::where('close_status', 'open')
                ->get()
                ->sum(function ($opp) {
                    return ($opp->expected_value * $opp->probability) / 100;
                });
            
            $wonThisMonth = Opportunity::where('close_status', 'won')
                ->whereMonth('actual_close_date', now()->month)
                ->whereYear('actual_close_date', now()->year)
                ->count();
            
            $wonValueThisMonth = Opportunity::where('close_status', 'won')
                ->whereMonth('actual_close_date', now()->month)
                ->whereYear('actual_close_date', now()->year)
                ->sum('expected_value');
            
            $avgDealSize = Opportunity::where('close_status', 'won')
                ->avg('expected_value');
            
            $totalClosed = Opportunity::whereIn('close_status', ['won', 'lost'])->count();
            $totalWon = Opportunity::where('close_status', 'won')->count();
            $winRate = $totalClosed > 0 ? round(($totalWon / $totalClosed) * 100, 1) : 0;
            
            return [
                Stat::make('Open Opportunities', $totalOpportunities)
                    ->description('Active pipeline')
                    ->descriptionIcon('heroicon-o-briefcase')
                    ->color('primary'),
                
                Stat::make('Pipeline Value', '₹' . number_format($pipelineValue / 100000, 2) . 'L')
                    ->description('Total expected value')
                    ->descriptionIcon('heroicon-o-currency-rupee')
                    ->color('success'),
                
                Stat::make('Weighted Pipeline', '₹' . number_format($weightedPipeline / 100000, 2) . 'L')
                    ->description('Value × Probability')
                    ->descriptionIcon('heroicon-o-calculator')
                    ->color('info'),
                
                Stat::make('Won This Month', $wonThisMonth)
                    ->description('₹' . number_format($wonValueThisMonth / 100000, 2) . 'L value')
                    ->descriptionIcon('heroicon-o-trophy')
                    ->color('success'),
                
                Stat::make('Win Rate', "{$winRate}%")
                    ->description("{$totalWon} won of {$totalClosed} closed")
                    ->descriptionIcon('heroicon-o-chart-bar')
                    ->color($winRate >= 50 ? 'success' : ($winRate >= 30 ? 'warning' : 'danger')),
                
                Stat::make('Avg Deal Size', '₹' . number_format(($avgDealSize ?? 0) / 100000, 2) . 'L')
                    ->description('Average won deal value')
                    ->descriptionIcon('heroicon-o-banknotes')
                    ->color('primary'),
            ];
        } catch (\Exception $e) {
            return [
                Stat::make('Open Opportunities', '0')
                    ->description('No data available')
                    ->descriptionIcon('heroicon-o-information-circle')
                    ->color('secondary'),
            ];
        }
    }
}
PHP
    ,

    'SiteVisitsTodayWidget.php' => <<<'PHP'
<?php

namespace App\Filament\Widgets;

use App\Models\SiteVisit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiteVisitsTodayWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected function getStats(): array
    {
        try {
            $todayVisits = SiteVisit::whereDate('scheduled_date', today())->count();
            $plannedToday = SiteVisit::whereDate('scheduled_date', today())
                ->where('status', 'Planned')->count();
            $confirmedToday = SiteVisit::whereDate('scheduled_date', today())
                ->where('status', 'Confirmed')->count();
            $completedToday = SiteVisit::whereDate('scheduled_date', today())
                ->where('status', 'Completed')->count();
            
            $upcomingThisWeek = SiteVisit::whereBetween('scheduled_date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->where('status', '!=', 'Completed')->count();
            
            $completionRate = $todayVisits > 0 ? round(($completedToday / $todayVisits) * 100, 1) : 0;
            
            return [
                Stat::make("Today's Site Visits", $todayVisits)
                    ->description('Total scheduled for today')
                    ->descriptionIcon('heroicon-o-map-pin')
                    ->color('primary'),
                
                Stat::make('Confirmed', $confirmedToday)
                    ->description('Ready for visit')
                    ->descriptionIcon('heroicon-o-check-circle')
                    ->color('success'),
                
                Stat::make('Completed Today', $completedToday)
                    ->description("{$completionRate}% completion rate")
                    ->descriptionIcon('heroicon-o-flag')
                    ->color($completionRate >= 80 ? 'success' : 'warning'),
                
                Stat::make('This Week', $upcomingThisWeek)
                    ->description('Upcoming site visits')
                    ->descriptionIcon('heroicon-o-calendar')
                    ->color('info'),
            ];
        } catch (\Exception $e) {
            return [
                Stat::make("Today's Site Visits", '0')
                    ->description('No data available')
                    ->descriptionIcon('heroicon-o-information-circle')
                    ->color('secondary'),
            ];
        }
    }
}
PHP
    ,

    'RevenueWidget.php' => <<<'PHP'
<?php

namespace App\Filament\Widgets;

use App\Models\Commission;
use App\Models\Opportunity;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RevenueWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected function getStats(): array
    {
        try {
            $wonValueThisMonth = Opportunity::where('close_status', 'won')
                ->whereMonth('actual_close_date', now()->month)
                ->whereYear('actual_close_date', now()->year)
                ->sum('expected_value');
            
            $commissionThisMonth = Commission::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('gross_commission');
            
            $paidCommissions = Commission::where('payment_status', 'Paid')
                ->whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('net_commission');
            
            $pendingCommissions = Commission::where('payment_status', 'Pending')
                ->sum('net_commission');
            
            return [
                Stat::make('Revenue This Month', '₹' . number_format($wonValueThisMonth / 100000, 2) . 'L')
                    ->description('From closed deals')
                    ->descriptionIcon('heroicon-o-currency-rupee')
                    ->color('success'),
                
                Stat::make('Commission Earned', '₹' . number_format($commissionThisMonth / 100000, 2) . 'L')
                    ->description('Gross commission this month')
                    ->descriptionIcon('heroicon-o-banknotes')
                    ->color('primary'),
                
                Stat::make('Paid Out', '₹' . number_format($paidCommissions / 100000, 2) . 'L')
                    ->description('Net commissions paid')
                    ->descriptionIcon('heroicon-o-check-badge')
                    ->color('success'),
                
                Stat::make('Pending Payout', '₹' . number_format($pendingCommissions / 100000, 2) . 'L')
                    ->description('Awaiting payment')
                    ->descriptionIcon('heroicon-o-clock')
                    ->color('warning'),
            ];
        } catch (\Exception $e) {
            return [
                Stat::make('Revenue This Month', '₹0')
                    ->description('No data available')
                    ->descriptionIcon('heroicon-o-information-circle')
                    ->color('secondary'),
            ];
        }
    }
}
PHP
];

// Create each widget file
foreach ($widgets as $filename => $content) {
    $filePath = $baseDir . '/' . $filename;
    file_put_contents($filePath, $content);
    echo "✅ Created {$filename}\n";
}

echo "\n🎉 All widgets created successfully!\n";
echo "\nWidgets created:\n";
echo "1. LeadStatsOverview.php\n";
echo "2. OpportunityPipelineWidget.php\n";
echo "3. SiteVisitsTodayWidget.php\n";
echo "4. RevenueWidget.php\n";
echo "\nThey will be auto-discovered by Filament on the dashboard.\n";
