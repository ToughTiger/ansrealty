<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\LeadSource;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class LeadSourceROIChart extends ChartWidget
{
    protected static ?string $heading = '💰 Lead Source ROI Analysis';
    
    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $sources = Lead::select('lead_source_id')
            ->selectRaw('COUNT(*) as total_leads')
            ->selectRaw('SUM(CASE WHEN converted_at IS NOT NULL THEN 1 ELSE 0 END) as converted')
            ->with('leadSource')
            ->groupBy('lead_source_id')
            ->having('total_leads', '>', 0)
            ->get();

        $labels = [];
        $totalLeads = [];
        $convertedLeads = [];
        $conversionRates = [];

        foreach ($sources as $source) {
            $sourceName = $source->leadSource?->name ?? 'Unknown';
            $labels[] = $sourceName;
            $totalLeads[] = $source->total_leads;
            $convertedLeads[] = $source->converted;
            $conversionRates[] = $source->total_leads > 0 
                ? round(($source->converted / $source->total_leads) * 100, 1) 
                : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Leads',
                    'data' => $totalLeads,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Converted Leads',
                    'data' => $convertedLeads,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'borderColor' => 'rgb(16, 185, 129)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Conversion Rate (%)',
                    'data' => $conversionRates,
                    'type' => 'line',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
                    'borderColor' => 'rgb(245, 158, 11)',
                    'borderWidth' => 3,
                    'yAxisID' => 'y1',
                    'fill' => false,
                ],
            ],
            'labels' => $labels,
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
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => 'Number of Leads',
                    ],
                ],
                'y1' => [
                    'beginAtZero' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'Conversion Rate (%)',
                    ],
                    'max' => 100,
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
        ];
    }
}
