<?php

namespace App\Filament\Resources\OpportunityResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class NegotiationsRelationManager extends RelationManager
{
    protected static string $relationship = 'negotiations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Negotiation Details')
                    ->schema([
                        Forms\Components\TextInput::make('offer_price')
                            ->label('Offer Price')
                            ->numeric()
                            ->prefix('₹')
                            ->required()
                            ->placeholder('5000000'),

                        Forms\Components\TextInput::make('counter_offer_price')
                            ->label('Counter Offer Price')
                            ->numeric()
                            ->prefix('₹')
                            ->placeholder('5200000'),

                        Forms\Components\TextInput::make('discount_amount')
                            ->label('Discount Amount')
                            ->numeric()
                            ->prefix('₹')
                            ->placeholder('50000'),

                        Forms\Components\TextInput::make('discount_percentage')
                            ->label('Discount %')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100),
                    ])->columns(2),

                Forms\Components\Section::make('Status & Approval')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'Pending' => 'Pending',
                                'In Progress' => 'In Progress',
                                'Accepted' => 'Accepted',
                                'Rejected' => 'Rejected',
                                'Counter Offered' => 'Counter Offered',
                            ])
                            ->default('Pending')
                            ->required()
                            ->live(),

                        Forms\Components\DatePicker::make('negotiation_date')
                            ->label('Negotiation Date')
                            ->default(now())
                            ->required(),

                        Forms\Components\Toggle::make('approved')
                            ->label('Approved')
                            ->live(),

                        Forms\Components\Select::make('approved_by')
                            ->label('Approved By')
                            ->relationship('approvedBy', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn (Forms\Get $get) => $get('approved')),
                    ])->columns(2),

                Forms\Components\Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->maxLength(65535)
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->rows(2)
                            ->columnSpanFull()
                            ->visible(fn (Forms\Get $get) => $get('status') === 'Rejected'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('negotiation_date')
            ->columns([
                Tables\Columns\TextColumn::make('negotiation_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('offer_price')
                    ->label('Offer')
                    ->money('INR')
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('counter_offer_price')
                    ->label('Counter Offer')
                    ->money('INR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('discount_amount')
                    ->label('Discount')
                    ->money('INR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('discount_percentage')
                    ->label('Discount %')
                    ->suffix('%')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'Pending',
                        'info' => 'In Progress',
                        'success' => 'Accepted',
                        'danger' => 'Rejected',
                        'primary' => 'Counter Offered',
                    ]),

                Tables\Columns\IconColumn::make('approved')
                    ->label('Approved')
                    ->boolean(),

                Tables\Columns\TextColumn::make('approvedBy.name')
                    ->label('Approved By')
                    ->searchable()
                    ->visible(fn ($record) => $record->approved),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->options([
                        'Pending' => 'Pending',
                        'In Progress' => 'In Progress',
                        'Accepted' => 'Accepted',
                        'Rejected' => 'Rejected',
                        'Counter Offered' => 'Counter Offered',
                    ]),

                Tables\Filters\TernaryFilter::make('approved')
                    ->label('Approval Status'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => !$record->approved)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'approved' => true,
                            'approved_by' => auth()->id(),
                        ]);
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('negotiation_date', 'desc');
    }
}
