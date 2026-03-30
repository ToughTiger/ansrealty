<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Analytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Analytics';
    protected static ?string $title = 'Analytics & Reports';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Reports';
    protected static string $view = 'filament.pages.analytics';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\PipelineValueWidget::class,
            \App\Filament\Widgets\SalesFunnelWidget::class,
            \App\Filament\Widgets\AgentPerformanceWidget::class,
            \App\Filament\Widgets\LeadSourceChart::class,
            \App\Filament\Widgets\LeadsChart::class,
            \App\Filament\Widgets\OpportunityByStage::class,
        ];
    }

    public function getWidgetData(): array
    {
        return [];
    }
}
