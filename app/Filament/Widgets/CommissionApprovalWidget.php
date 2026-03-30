<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CommissionApprovalWidget extends BaseWidget
{
    protected static ?int $sort = 9;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = '💰 Commission Approvals - Pending Manager Action';
    protected static ?string $pollingInterval = '2min';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->where('commission_status', 'Pending')
                    ->whereNotNull('agent_commission_amount')
                    ->with(['agent', 'customer', 'property', 'employee'])
                    ->orderBy('created_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('booking_number')
                    ->label('Booking #')
                    ->badge()
                    ->color('info')
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Booking Date')
                    ->date('M d, Y')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('agent.name')
                    ->label('Agent')
                    ->searchable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('customer.full_name')
                    ->label('Customer')
                    ->searchable()
                    ->limit(25),
                    
                Tables\Columns\TextColumn::make('property.name')
                    ->label('Property')
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('property_value')
                    ->label('Property Value')
                    ->money('INR')
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('agent_commission_percentage')
                    ->label('Commission %')
                    ->suffix('%')
                    ->alignCenter(),
                    
                Tables\Columns\TextColumn::make('agent_commission_amount')
                    ->label('Commission Amount')
                    ->money('INR')
                    ->weight('bold')
                    ->color('warning'),
                    
                Tables\Columns\TextColumn::make('booking_stage')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Token Received', 'Token Confirmed' => 'warning',
                        'Agreement Signed' => 'success',
                        'Registration Done', 'Completed' => 'info',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Commission')
                    ->modalDescription(fn ($record) => 'Approve commission of ₹' . number_format($record->agent_commission_amount, 2) . ' for ' . $record->agent->full_name . '?')
                    ->action(function ($record) {
                        $record->update([
                            'commission_status' => 'Approved',
                        ]);
                    })
                    ->successNotificationTitle('Commission approved successfully')
                    ->after(fn () => $this->dispatch('$refresh')),
                    
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-m-eye')
                    ->url(fn ($record) => route('filament.admin.resources.bookings.view', ['record' => $record->id])),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('approve_all')
                    ->label('Approve Selected')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        $records->each(fn ($record) => $record->update(['commission_status' => 'Approved']));
                    })
                    ->successNotificationTitle('Commissions approved')
                    ->deselectRecordsAfterCompletion(),
            ])
            ->emptyStateHeading('🎉 No pending approvals')
            ->emptyStateDescription('All commissions are approved or paid')
            ->emptyStateIcon('heroicon-o-check-badge');
    }
}
