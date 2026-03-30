<?php

namespace App\Filament\Widgets;

use App\Models\Opportunity;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OpportunityByStage extends ChartWidget
{
    protected static ?string $heading = '🎯 Active Opportunities Pipeline';
    protected static ?int $sort = 12;
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '5min';

    protected function getData(): array
    {
        $data = Opportunity::query()
            ->join('opportunity_stages', 'opportunities.opportunity_stage_id', '=', 'opportunity_stages.id')
            ->select('opportunity_stages.name', DB::raw('count(*) as total'))
            ->where('opportunities.close_status', 'Open')
            ->groupBy('opportunity_stages.name')
            ->orderBy('opportunity_stages.order')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Active Opportunities',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(168, 85, 247, 0.7)',
                        'rgba(236, 72, 153, 0.7)',
                    ],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
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
        ];
    }
}
