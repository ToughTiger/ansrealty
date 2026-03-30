<?php

namespace App\Filament\Widgets;

use App\Models\Agent;
use App\Models\User;
use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class AgentPerformanceWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = '🏆 Top Performing Agents - Leaderboard';
    protected static ?string $pollingInterval = '2min';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Agent::query()
                    ->where('status', 'Active')
                    ->withCount(['bookings as total_deals'])
                    ->withSum('bookings as total_commission', 'agent_commission_amount')
                    ->withSum(['bookings as commission_paid' => function ($query) {
                        $query->where('commission_status', 'Paid');
                    }], 'commission_paid')
                    ->orderByDesc('total_commission')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('rank')
                    ->label('🏅 Rank')
                    ->state(fn ($rowLoop) => $rowLoop->iteration)
                    ->badge()
                    ->size('lg')
                    ->color(fn ($rowLoop) => match($rowLoop->iteration) {
                        1 => 'warning',
                        2 => 'gray',
                        3 => 'danger',
                        default => 'info'
                    })
                    ->icon(fn ($rowLoop) => match($rowLoop->iteration) {
                        1 => 'heroicon-s-trophy',
                        2 => 'heroicon-s-star',
                        3 => 'heroicon-s-fire',
                        default => 'heroicon-s-user'
                    }),
                    
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Agent Name')
                    ->searchable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('agent_code')
                    ->label('Code')
                    ->badge()
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('total_deals')
                    ->label('Deals Closed')
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn ($state) => $state ?? 0),
                    
                Tables\Columns\TextColumn::make('total_commission')
                    ->label('Total Commission')
                    ->money('INR')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
                    
                Tables\Columns\TextColumn::make('commission_paid')
                    ->label('Paid')
                    ->money('INR')
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('pending')
                    ->label('Pending')
                    ->state(fn ($record) => ($record->total_commission ?? 0) - ($record->commission_paid ?? 0))
                    ->money('INR')
                    ->color('warning'),
                    
                Tables\Columns\TextColumn::make('assignedEmployee.name')
                    ->label('Handled By')
                    ->badge()
                    ->color('gray'),
            ])
            ->defaultSort('total_commission', 'desc');
    }
}
