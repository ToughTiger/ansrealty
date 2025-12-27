<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class LeadsChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bubble';
    }
}
