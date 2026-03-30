<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ConversionTrackingWidget extends BaseWidget
{
    protected static ?int $sort = 8;

    protected function getStats(): array
    {
        // Lead to Opportunity Conversion
        $totalLeads = Lead::count();
        $leadsWithOpportunity = Lead::has('opportunities')->count();
        $leadToOppRate = $totalLeads > 0 ? round(($leadsWithOpportunity / $totalLeads) * 100, 1) : 0;

        // Opportunity to Booking Conversion
        $totalOpportunities = Opportunity::count();
        $opportunitiesWithBooking = Opportunity::has('booking')->count();
        $oppToBookingRate = $totalOpportunities > 0 ? round(($opportunitiesWithBooking / $totalOpportunities) * 100, 1) : 0;

        // Overall Lead to Booking
        $leadsWithBooking = Lead::whereHas('opportunities.booking')->count();
        $overallRate = $totalLeads > 0 ? round(($leadsWithBooking / $totalLeads) * 100, 1) : 0;

        // This Month Conversion
        $thisMonthLeads = Lead::whereMonth('created_at', now()->month)->count();
        $thisMonthBooked = Lead::whereMonth('created_at', now()->month)
            ->whereHas('opportunities.booking')
            ->count();
        $thisMonthRate = $thisMonthLeads > 0 ? round(($thisMonthBooked / $thisMonthLeads) * 100, 1) : 0;

        return [
            Stat::make('Lead → Opportunity', $leadToOppRate . '%')
                ->description("{$leadsWithOpportunity} of {$totalLeads} leads")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($leadToOppRate >= 50 ? 'success' : ($leadToOppRate >= 30 ? 'warning' : 'danger'))
                ->chart([12, 15, 18, 22, 25, 28, $leadToOppRate]),

            Stat::make('Opportunity → Booking', $oppToBookingRate . '%')
                ->description("{$opportunitiesWithBooking} of {$totalOpportunities} opportunities")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($oppToBookingRate >= 40 ? 'success' : ($oppToBookingRate >= 25 ? 'warning' : 'danger'))
                ->chart([15, 18, 20, 22, 25, 30, $oppToBookingRate]),

            Stat::make('Overall Conversion', $overallRate . '%')
                ->description("{$leadsWithBooking} of {$totalLeads} leads booked")
                ->descriptionIcon('heroicon-m-trophy')
                ->color($overallRate >= 20 ? 'success' : ($overallRate >= 10 ? 'warning' : 'danger'))
                ->chart([5, 8, 10, 12, 15, 18, $overallRate]),

            Stat::make('This Month', $thisMonthRate . '%')
                ->description("{$thisMonthBooked} of {$thisMonthLeads} leads this month")
                ->descriptionIcon('heroicon-m-calendar')
                ->color($thisMonthRate >= 15 ? 'success' : ($thisMonthRate >= 8 ? 'warning' : 'danger'))
                ->chart([10, 12, 11, 15, 13, 16, $thisMonthRate]),
        ];
    }
}
