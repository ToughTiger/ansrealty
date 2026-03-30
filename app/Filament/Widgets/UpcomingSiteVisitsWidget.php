<?php

namespace App\Filament\Widgets;

use App\Models\SiteVisit;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class UpcomingSiteVisitsWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = '📅 Today\'s Site Visits Schedule';
    protected static ?string $pollingInterval = '1min';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                SiteVisit::query()
                    ->whereDate('scheduled_at', now()->toDateString())
                    ->with(['lead', 'property', 'assignedAgent'])
                    ->orderBy('scheduled_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Time')
                    ->time('h:i A')
                    ->badge()
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('lead.full_name')
                    ->label('Customer')
                    ->searchable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('lead.mobile')
                    ->label('Mobile')
                    ->icon('heroicon-m-phone'),
                    
                Tables\Columns\TextColumn::make('property.name')
                    ->label('Property')
                    ->searchable()
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('property.location')
                    ->label('Location')
                    ->icon('heroicon-m-map-pin')
                    ->limit(20),
                    
                Tables\Columns\TextColumn::make('assignedAgent.name')
                    ->label('Agent')
                    ->badge()
                    ->color('success'),
                    
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Planned', 'Confirmed' => 'warning',
                        'Completed' => 'success',
                        'Cancelled', 'No Show' => 'danger',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('agent_notes')
                    ->label('Notes')
                    ->limit(30)
                    ->placeholder('—'),
            ])
            ->emptyStateHeading('No site visits scheduled for today')
            ->emptyStateDescription('Site visits will appear here once scheduled')
            ->emptyStateIcon('heroicon-o-calendar');
    }
}
