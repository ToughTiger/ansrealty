<?php

namespace App\Filament\Widgets;

use App\Models\Communication;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CommunicationHistoryWidget extends BaseWidget
{
    protected static ?int $sort = 9;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Communication::query()
                    ->with(['user', 'communicable'])
                    ->latest()
                    ->limit(50)
            )
            ->columns([
                Tables\Columns\BadgeColumn::make('communication_type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'email',
                        'success' => 'whatsapp',
                        'warning' => 'sms',
                        'info' => 'call',
                    ])
                    ->icons([
                        'heroicon-o-envelope' => 'email',
                        'heroicon-o-chat-bubble-left-right' => 'whatsapp',
                        'heroicon-o-device-phone-mobile' => 'sms',
                        'heroicon-o-phone' => 'call',
                    ]),

                Tables\Columns\TextColumn::make('direction')
                    ->badge()
                    ->colors([
                        'success' => 'outbound',
                        'info' => 'inbound',
                    ]),

                Tables\Columns\TextColumn::make('recipient')
                    ->searchable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('message')
                    ->searchable()
                    ->limit(40)
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'sent',
                        'info' => 'delivered',
                        'primary' => 'read',
                        'danger' => 'failed',
                    ]),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Sent By')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('communicable_type')
                    ->label('Related To')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('sent_at')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('communication_type')
                    ->options([
                        'email' => 'Email',
                        'whatsapp' => 'WhatsApp',
                        'sms' => 'SMS',
                        'call' => 'Call',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'sent' => 'Sent',
                        'delivered' => 'Delivered',
                        'read' => 'Read',
                        'failed' => 'Failed',
                    ]),

                Tables\Filters\Filter::make('today')
                    ->label('Today')
                    ->query(fn ($query) => $query->whereDate('created_at', today())),
            ])
            ->heading('💬 Recent Communication History');
    }
}
