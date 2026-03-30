<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class HotLeadsWidget extends BaseWidget
{
    protected static ?int $sort = 8;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = '🔥 Hot Leads - Requiring Immediate Action';
    protected static ?string $pollingInterval = '1min';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Lead::query()
                    ->where('priority', 'Hot')
                    ->whereDoesntHave('opportunities')
                    ->with(['leadSource', 'leadStatus', 'assignedAgent'])
                    ->orderByDesc('created_at')
                    ->limit(15)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added')
                    ->since()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Customer')
                    ->searchable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('mobile')
                    ->label('Mobile')
                    ->icon('heroicon-m-phone')
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('budget_min')
                    ->label('Budget')
                    ->formatStateUsing(fn ($record) => 
                        '₹' . number_format($record->budget_min / 100000, 2) . 'L - ₹' . number_format($record->budget_max / 100000, 2) . 'L'
                    ),
                    
                Tables\Columns\TextColumn::make('preferred_locations')
                    ->label('Location')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
                    ->limit(20)
                    ->icon('heroicon-m-map-pin'),
                    
                Tables\Columns\TextColumn::make('property_types')
                    ->label('Property Type')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
                    ->badge(),
                    
                Tables\Columns\TextColumn::make('leadSource.name')
                    ->label('Source')
                    ->badge()
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('assignedAgent.name')
                    ->label('Assigned To')
                    ->badge()
                    ->color('success')
                    ->default('Unassigned'),
                    
                Tables\Columns\TextColumn::make('leadStatus.name')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($record) => match($record->leadStatus?->name) {
                        'New' => 'danger',
                        'Contacted' => 'warning',
                        'Qualified' => 'success',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('convert')
                    ->label('Convert to Opportunity')
                    ->icon('heroicon-m-arrow-right-circle')
                    ->color('success')
                    ->url(fn ($record) => route('filament.admin.resources.opportunities.create', ['lead_id' => $record->id]))
                    ->openUrlInNewTab(),
            ])
            ->emptyStateHeading('No hot leads at the moment')
            ->emptyStateDescription('Hot leads will appear here once added')
            ->emptyStateIcon('heroicon-o-fire');
    }
}
