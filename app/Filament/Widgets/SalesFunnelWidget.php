<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesFunnelWidget extends ChartWidget
{
    protected static ?string $heading = '📊 Sales Funnel - Conversion Analysis';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '60s';

    protected function getData(): array
    {
        // Get counts for each stage
        $totalLeads = Lead::count();
        $qualifiedLeads = Lead::where('priority', '!=', 'Cold')->count();
        $totalOpportunities = Opportunity::count();
        $activeOpportunities = Opportunity::where('close_status', 'Open')->count();
        $totalBookings = Booking::count();
        $completedBookings = Booking::where('booking_stage', 'Completed')->count();

        // Calculate conversion rates
        $leadToOpp = $totalLeads > 0 ? round(($totalOpportunities / $totalLeads) * 100, 1) : 0;
        $oppToBooking = $totalOpportunities > 0 ? round(($totalBookings / $totalOpportunities) * 100, 1) : 0;
        $bookingToClosed = $totalBookings > 0 ? round(($completedBookings / $totalBookings) * 100, 1) : 0;

        return [
            'datasets' => [
                [
                    'label' => 'Sales Funnel',
                    'data' => [$totalLeads, $qualifiedLeads, $totalOpportunities, $totalBookings, $completedBookings],
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.5)',  // Blue
                        'rgba(16, 185, 129, 0.5)',  // Green
                        'rgba(245, 158, 11, 0.5)',  // Amber
                        'rgba(239, 68, 68, 0.5)',   // Red
                        'rgba(168, 85, 247, 0.5)',  // Purple
                    ],
                    'borderColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)',
                        'rgb(168, 85, 247)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => [
                "All Leads ({$totalLeads})",
                "Qualified ({$qualifiedLeads}) - {$leadToOpp}%",
                "Opportunities ({$totalOpportunities})",
                "Bookings ({$totalBookings}) - {$oppToBooking}%",
                "Closed ({$completedBookings}) - {$bookingToClosed}%"
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
