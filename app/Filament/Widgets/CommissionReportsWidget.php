<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class CommissionReportsWidget extends BaseWidget
{
    protected static ?int $sort = 7;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->with(['opportunity.lead', 'agent', 'employee'])
                    ->where('commission_status', '!=', 'Not Applicable')
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('booking_number')
                    ->label('Booking #')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('opportunity.lead.full_name')
                    ->label('Customer')
                    ->searchable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('agent.name')
                    ->label('Agent')
                    ->searchable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('deal_value')
                    ->label('Deal Value')
                    ->money('INR')
                    ->sortable()
                    ->color('success')
                    ->weight('bold')
                    ->getStateUsing(fn($record) => $record->property_value),

                Tables\Columns\TextColumn::make('commission_percentage')
                    ->label('Commission %')
                    ->suffix('%')
                    ->alignCenter()
                    ->sortable()
                    ->getStateUsing(fn($record) => $record->agent_commission_percentage),

                Tables\Columns\TextColumn::make('commission_amount')
                    ->label('Commission Amount')
                    ->money('INR')
                    ->sortable()
                    ->color('primary')
                    ->weight('bold')
                    ->getStateUsing(fn($record) => $record->agent_commission_amount),

                Tables\Columns\BadgeColumn::make('commission_status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'Pending Approval',
                        'info' => 'Approved',
                        'success' => 'Paid',
                        'danger' => 'Rejected',
                        'gray' => 'Not Applicable',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('commission_paid_at')
                    ->label('Paid On')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Booking Date')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('commission_status')
                    ->options([
                        'Pending Approval' => 'Pending Approval',
                        'Approved' => 'Approved',
                        'Paid' => 'Paid',
                        'Rejected' => 'Rejected',
                    ]),
                Tables\Filters\Filter::make('this_month')
                    ->label('This Month')
                    ->query(fn (Builder $query) => $query->whereMonth('created_at', now()->month)),
            ])
            ->defaultSort('created_at', 'desc')
            ->heading('💵 Commission Reports');
    }
}
