<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentBookingsWidget extends BaseWidget
{
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = '🎉 Recent Bookings - Last 7 Days Wins';
    protected static ?string $pollingInterval = '2min';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->where('created_at', '>=', now()->subDays(7))
                    ->with(['customer', 'property', 'agent', 'employee'])
                    ->orderByDesc('created_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('booking_number')
                    ->label('Booking #')
                    ->badge()
                    ->color('success')
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Booked')
                    ->since()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('customer.full_name')
                    ->label('Customer')
                    ->searchable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('property.name')
                    ->label('Property')
                    ->searchable()
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('property_value')
                    ->label('Value')
                    ->money('INR')
                    ->color('success')
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('booking_stage')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Token Received', 'Token Confirmed' => 'warning',
                        'Agreement Signed', 'Registration Done' => 'success',
                        'Completed' => 'info',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('agent.name')
                    ->label('Agent')
                    ->badge()
                    ->color('primary')
                    ->default('Direct'),
                    
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Employee')
                    ->badge()
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('agent_commission_amount')
                    ->label('Commission')
                    ->money('INR')
                    ->color('warning'),
            ])
            ->emptyStateHeading('No recent bookings')
            ->emptyStateDescription('Bookings will appear here once created')
            ->emptyStateIcon('heroicon-o-clipboard-document-check');
    }
}
