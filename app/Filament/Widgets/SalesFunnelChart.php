<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\LeadStatus;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesFunnelChart extends ChartWidget
{
    protected static ?string $heading = '🔄 Sales Funnel - Lead Pipeline';
    
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $statuses = LeadStatus::withCount('leads')->orderBy('order')->get();

        $colors = [
            'rgba(59, 130, 246, 0.8)',   // Blue
            'rgba(16, 185, 129, 0.8)',   // Green
            'rgba(245, 158, 11, 0.8)',   // Orange
            'rgba(239, 68, 68, 0.8)',    // Red
            'rgba(139, 92, 246, 0.8)',   // Purple
            'rgba(236, 72, 153, 0.8)',   // Pink
            'rgba(20, 184, 166, 0.8)',   // Teal
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Number of Leads',
                    'data' => $statuses->pluck('leads_count')->toArray(),
                    'backgroundColor' => array_slice($colors, 0, $statuses->count()),
                    'borderWidth' => 2,
                    'borderColor' => '#fff',
                ],
            ],
            'labels' => $statuses->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}
