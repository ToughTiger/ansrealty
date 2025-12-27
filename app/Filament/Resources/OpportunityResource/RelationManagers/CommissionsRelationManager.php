<?php

namespace App\Filament\Resources\OpportunityResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CommissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'commissions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Commission Details')
                    ->schema([
                        Forms\Components\Select::make('property_id')
                            ->label('Property')
                            ->relationship('property', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('agent_id')
                            ->label('Primary Agent')
                            ->relationship('agent', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('deal_value')
                            ->label('Deal Value')
                            ->numeric()
                            ->prefix('₹')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $commissionPercentage = $get('commission_percentage') ?? 0;
                                $grossCommission = ($state * $commissionPercentage) / 100;
                                $set('gross_commission', $grossCommission);
                                
                                $tdsPercentage = $get('tds_percentage') ?? 0;
                                $tdsAmount = ($grossCommission * $tdsPercentage) / 100;
                                $set('tds_amount', $tdsAmount);
                                
                                $otherDeductions = $get('other_deductions') ?? 0;
                                $netCommission = $grossCommission - $tdsAmount - $otherDeductions;
                                $set('net_commission', $netCommission);
                            }),
                    ])->columns(3),

                Forms\Components\Section::make('Commission Calculation')
                    ->schema([
                        Forms\Components\TextInput::make('commission_percentage')
                            ->label('Commission %')
                            ->numeric()
                            ->suffix('%')
                            ->default(2)
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $dealValue = $get('deal_value') ?? 0;
                                $grossCommission = ($dealValue * $state) / 100;
                                $set('gross_commission', $grossCommission);
                                
                                $tdsPercentage = $get('tds_percentage') ?? 0;
                                $tdsAmount = ($grossCommission * $tdsPercentage) / 100;
                                $set('tds_amount', $tdsAmount);
                                
                                $otherDeductions = $get('other_deductions') ?? 0;
                                $netCommission = $grossCommission - $tdsAmount - $otherDeductions;
                                $set('net_commission', $netCommission);
                            }),

                        Forms\Components\TextInput::make('gross_commission')
                            ->label('Gross Commission')
                            ->numeric()
                            ->prefix('₹')
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('tds_percentage')
                            ->label('TDS %')
                            ->numeric()
                            ->suffix('%')
                            ->default(1)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $grossCommission = $get('gross_commission') ?? 0;
                                $tdsAmount = ($grossCommission * $state) / 100;
                                $set('tds_amount', $tdsAmount);
                                
                                $otherDeductions = $get('other_deductions') ?? 0;
                                $netCommission = $grossCommission - $tdsAmount - $otherDeductions;
                                $set('net_commission', $netCommission);
                            }),

                        Forms\Components\TextInput::make('tds_amount')
                            ->label('TDS Amount')
                            ->numeric()
                            ->prefix('₹')
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('other_deductions')
                            ->label('Other Deductions')
                            ->numeric()
                            ->prefix('₹')
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $grossCommission = $get('gross_commission') ?? 0;
                                $tdsAmount = $get('tds_amount') ?? 0;
                                $netCommission = $grossCommission - $tdsAmount - $state;
                                $set('net_commission', $netCommission);
                            }),

                        Forms\Components\TextInput::make('net_commission')
                            ->label('Net Commission')
                            ->numeric()
                            ->prefix('₹')
                            ->disabled()
                            ->dehydrated()
                            ->extraAttributes(['class' => 'font-bold']),
                    ])->columns(3),

                Forms\Components\Section::make('Split & Payment')
                    ->schema([
                        Forms\Components\Select::make('split_agent_id')
                            ->label('Split Agent (Optional)')
                            ->relationship('splitAgent', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('split_percentage')
                            ->label('Split %')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100),

                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'Pending' => 'Pending',
                                'Processing' => 'Processing',
                                'Paid' => 'Paid',
                                'On Hold' => 'On Hold',
                            ])
                            ->default('Pending')
                            ->required()
                            ->live(),

                        Forms\Components\DatePicker::make('payment_date')
                            ->label('Payment Date')
                            ->visible(fn (Forms\Get $get) => $get('payment_status') === 'Paid'),

                        Forms\Components\Toggle::make('approved')
                            ->label('Approved')
                            ->live(),

                        Forms\Components\Select::make('approved_by')
                            ->label('Approved By')
                            ->relationship('approvedBy', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn (Forms\Get $get) => $get('approved')),

                        Forms\Components\Textarea::make('notes')
                            ->maxLength(65535)
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('deal_value')
            ->columns([
                Tables\Columns\TextColumn::make('property.name')
                    ->label('Property')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('agent.name')
                    ->label('Agent')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deal_value')
                    ->label('Deal Value')
                    ->money('INR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('commission_percentage')
                    ->label('Rate')
                    ->suffix('%')
                    ->sortable(),

                Tables\Columns\TextColumn::make('gross_commission')
                    ->label('Gross')
                    ->money('INR')
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('net_commission')
                    ->label('Net')
                    ->money('INR')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'Pending',
                        'info' => 'Processing',
                        'success' => 'Paid',
                        'danger' => 'On Hold',
                    ]),

                Tables\Columns\IconColumn::make('approved')
                    ->label('Approved')
                    ->boolean(),

                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Paid On')
                    ->date('d M Y')
                    ->sortable()
                    ->visible(fn ($record) => $record->payment_status === 'Paid'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->multiple()
                    ->options([
                        'Pending' => 'Pending',
                        'Processing' => 'Processing',
                        'Paid' => 'Paid',
                        'On Hold' => 'On Hold',
                    ]),

                Tables\Filters\TernaryFilter::make('approved')
                    ->label('Approval Status'),

                Tables\Filters\Filter::make('high_value')
                    ->label('High Value (>1L)')
                    ->query(fn ($query) => $query->where('gross_commission', '>', 100000)),
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
                Tables\Actions\Action::make('markPaid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-currency-rupee')
                    ->color('success')
                    ->visible(fn ($record) => $record->payment_status !== 'Paid')
                    ->form([
                        Forms\Components\DatePicker::make('payment_date')
                            ->label('Payment Date')
                            ->default(now())
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'payment_status' => 'Paid',
                            'payment_date' => $data['payment_date'],
                        ]);
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('bulkApprove')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update([
                            'approved' => true,
                            'approved_by' => auth()->id(),
                        ])),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
