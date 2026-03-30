<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Pipeline & Key Metrics --}}
        <div>
            <h2 class="text-xl font-bold mb-4">Pipeline Overview</h2>
            @livewire(\App\Filament\Widgets\PipelineValueWidget::class)
        </div>

        {{-- Sales Funnel --}}
        <div>
            <h2 class="text-xl font-bold mb-4">Sales Funnel</h2>
            @livewire(\App\Filament\Widgets\SalesFunnelWidget::class)
        </div>

        {{-- Agent Performance --}}
        <div>
            <h2 class="text-xl font-bold mb-4">Agent Performance</h2>
            @livewire(\App\Filament\Widgets\AgentPerformanceWidget::class)
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-bold mb-4">Lead Sources</h2>
                @livewire(\App\Filament\Widgets\LeadSourceChart::class)
            </div>
            <div>
                <h2 class="text-xl font-bold mb-4">Opportunities by Stage</h2>
                @livewire(\App\Filament\Widgets\OpportunityByStage::class)
            </div>
        </div>

        {{-- Trends --}}
        <div>
            <h2 class="text-xl font-bold mb-4">Lead Trends</h2>
            @livewire(\App\Filament\Widgets\LeadsChart::class)
        </div>
    </div>
</x-filament-panels::page>
