<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class LeadsChart extends ChartWidget
{
    protected static ?string $heading = '📈 Lead Generation Trend - Last 6 Months';
    protected static ?int $sort = 11;
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '5min';

    protected function getData(): array
    {
        // Get last 6 months data
        $data = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Lead::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $data->push([
                'month' => $date->format('M Y'),
                'count' => $count,
            ]);
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Leads',
                    'data' => $data->pluck('count')->toArray(),
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
