<?php

namespace App\Filament\Widgets;

use App\Models\Property;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PropertyByType extends ChartWidget
{
    protected static ?string $heading = '🏘️ Property Inventory Distribution';
    protected static ?int $sort = 13;
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10min';

    public ?string $filter = 'available';

    protected function getData(): array
    {
        $query = Property::query()
            ->select('property_type', DB::raw('count(*) as total'))
            ->groupBy('property_type');

        // Apply filter
        if ($this->filter === 'available') {
            $query->where('availability_status', 'Available')
                  ->where('is_active', true);
        } elseif ($this->filter === 'sold') {
            $query->where('availability_status', 'Sold');
        }

        $data = $query->get();

        return [
            'datasets' => [
                [
                    'label' => 'Properties',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(168, 85, 247, 0.7)',
                    ],
                ],
            ],
            'labels' => $data->pluck('property_type')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getFilters(): ?array
    {
        return [
            'all' => 'All Properties',
            'available' => 'Available',
            'sold' => 'Sold',
        ];
    }
}
