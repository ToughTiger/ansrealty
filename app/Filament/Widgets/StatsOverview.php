<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\Property;
use App\Models\Commission;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;
    protected static ?string $pollingInterval = '30s';
    
    protected function getStats(): array
    {
        // Get current and last month data for trends
        $leadsThisMonth = Lead::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        $leadsLastMonth = Lead::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        
        $leadsTrend = $leadsLastMonth > 0 
            ? round((($leadsThisMonth - $leadsLastMonth) / $leadsLastMonth) * 100, 1)
            : 0;
        
        // Opportunities stats
        $activeOpportunities = Opportunity::where('close_status', 'Open')->count();
        $wonOpportunities = Opportunity::where('close_status', 'Won')
            ->whereMonth('created_at', now()->month)
            ->count();
        
        $totalOpportunities = Opportunity::whereMonth('created_at', now()->month)->count();
        $conversionRate = $totalOpportunities > 0 
            ? round(($wonOpportunities / $totalOpportunities) * 100, 1)
            : 0;
        
        // Properties stats
        $availableProperties = Property::where('is_active', true)
            ->where('availability_status', 'Available')
            ->count();
        
        $totalProperties = Property::count();
        
        // Revenue stats
        $monthlyRevenue = Commission::where('status', 'Paid')
            ->whereMonth('payment_date', now()->month)
            ->sum('net_commission');
        
        $expectedRevenue = Opportunity::where('close_status', 'Open')
            ->sum('expected_value');

        return [
            Stat::make('📊 Total Leads', Lead::count())
                ->description($leadsTrend >= 0 ? "↗ +{$leadsTrend}% from last month" : "↘ {$leadsTrend}% from last month")
                ->descriptionIcon($leadsTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($leadsTrend >= 0 ? 'success' : 'danger')
                ->chart([7, 12, 15, 18, 22, 25, $leadsThisMonth])
                ->extraAttributes([
                    'class' => 'bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-950 dark:to-blue-900',
                ]),
            
            Stat::make('🎯 Active Opportunities', $activeOpportunities)
                ->description("✨ {$conversionRate}% conversion rate")
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info')
                ->chart([3, 5, 7, 9, 12, 15, $activeOpportunities])
                ->extraAttributes([
                    'class' => 'bg-gradient-to-br from-sky-50 to-sky-100 dark:from-sky-950 dark:to-sky-900',
                ]),
            
            Stat::make('🏢 Available Properties', $availableProperties)
                ->description("📦 {$totalProperties} total in inventory")
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-950 dark:to-amber-900',
                ]),
            
            Stat::make('💵 Monthly Revenue', '₹' . number_format($monthlyRevenue / 100000, 2) . 'L')
                ->description('💰 ₹' . number_format($expectedRevenue / 100000, 2) . 'L in pipeline')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->extraAttributes([
                    'class' => 'bg-gradient-to-br from-green-50 to-green-100 dark:from-green-950 dark:to-green-900',
                ]),
        ];
    }
}
