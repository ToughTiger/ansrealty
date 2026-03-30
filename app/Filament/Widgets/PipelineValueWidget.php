<?php

namespace App\Filament\Widgets;

use App\Models\Opportunity;
use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PipelineValueWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        // Pipeline value (open opportunities)
        $pipelineValue = Opportunity::where('close_status', 'Open')
            ->sum('expected_value');
            
        $weightedPipeline = Opportunity::where('close_status', 'Open')
            ->selectRaw('SUM(expected_value * probability / 100) as weighted_value')
            ->first()
            ->weighted_value ?? 0;

        // Bookings in progress
        $bookingsInProgress = Booking::whereNotIn('booking_stage', ['Completed', 'Cancelled'])
            ->count();
            
        $bookingsValue = Booking::whereNotIn('booking_stage', ['Completed', 'Cancelled'])
            ->sum('property_value');

        // Commission pending
        $commissionPending = Booking::where('commission_status', '!=', 'Paid')
            ->whereNotNull('agent_commission_amount')
            ->sum('agent_commission_amount');
            
        $commissionPendingApproval = Booking::where('commission_status', 'Pending')
            ->whereNotNull('agent_commission_amount')
            ->sum('agent_commission_amount');

        // This month bookings
        $thisMonthBookings = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $thisMonthValue = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('property_value');

        return [
            Stat::make('💰 Pipeline Value', '₹' . number_format($pipelineValue / 100000, 2) . 'L')
                ->description('Weighted: ₹' . number_format($weightedPipeline / 100000, 2) . 'L')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info')
                ->chart([65, 72, 85, 92, 105, 120, round($pipelineValue / 100000)])
                ->extraAttributes([
                    'class' => 'bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-950 dark:to-blue-900',
                ]),
                
            Stat::make('🏗️ Active Bookings', $bookingsInProgress)
                ->description('Value: ₹' . number_format($bookingsValue / 100000, 2) . 'L')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-950 dark:to-amber-900',
                ]),
                
            Stat::make('💳 Commission Pending', '₹' . number_format($commissionPending / 100000, 2) . 'L')
                ->description('Awaiting approval: ₹' . number_format($commissionPendingApproval / 100000, 2) . 'L')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger')
                ->url(route('filament.admin.resources.bookings.index', [
                    'tableFilters[commission_status][value]' => 'Pending'
                ]))
                ->extraAttributes([
                    'class' => 'bg-gradient-to-br from-red-50 to-red-100 dark:from-red-950 dark:to-red-900',
                ]),
                
            Stat::make('🎉 This Month', $thisMonthBookings . ' Bookings')
                ->description('Value: ₹' . number_format($thisMonthValue / 100000, 2) . 'L')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success')
                ->chart([2, 3, 5, 7, 8, 10, $thisMonthBookings])
                ->extraAttributes([
                    'class' => 'bg-gradient-to-br from-green-50 to-green-100 dark:from-green-950 dark:to-green-900',
                ]),
        ];
    }
}
