<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\LeadSource;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class LeadSourceChart extends ChartWidget
{
    protected static ?string $heading = '🎯 Lead Sources Distribution';
    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '350px';
    protected static ?string $pollingInterval = '5min';

    public ?string $filter = 'month';

    protected function getData(): array
    {
        $query = Lead::query()
            ->join('lead_sources', 'leads.lead_source_id', '=', 'lead_sources.id')
            ->select('lead_sources.name', DB::raw('count(*) as total'))
            ->groupBy('lead_sources.name');

        // Apply date filter
        match ($this->filter) {
            'today' => $query->whereDate('leads.created_at', today()),
            'week' => $query->whereBetween('leads.created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $query->whereMonth('leads.created_at', now()->month)->whereYear('leads.created_at', now()->year),
            'quarter' => $query->whereBetween('leads.created_at', [now()->startOfQuarter(), now()->endOfQuarter()]),
            'year' => $query->whereYear('leads.created_at', now()->year),
            default => null,
        };

        $data = $query->orderByDesc('total')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Leads by Source',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.7)',  // Blue
                        'rgba(16, 185, 129, 0.7)',  // Green
                        'rgba(245, 158, 11, 0.7)',  // Amber
                        'rgba(239, 68, 68, 0.7)',   // Red
                        'rgba(168, 85, 247, 0.7)',  // Purple
                        'rgba(236, 72, 153, 0.7)',  // Pink
                        'rgba(20, 184, 166, 0.7)',  // Teal
                    ],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'This Week',
            'month' => 'This Month',
            'quarter' => 'This Quarter',
            'year' => 'This Year',
            'all' => 'All Time',
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
