<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommissionResource\Pages;
use App\Models\Commission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommissionResource extends Resource
{
    protected static ?string $model = Commission::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-rupee';
    
    protected static ?string $navigationGroup = 'Finance';
    
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Commission Details')
                    ->schema([
                        Forms\Components\Select::make('opportunity_id')
                            ->label('Opportunity')
                            ->relationship('opportunity', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Select::make('property_id')
                            ->label('Property')
                            ->relationship('property', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Select::make('agent_id')
                            ->label('Agent')
                            ->relationship('agent', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('deal_value')
                            ->label('Deal Value')
                            ->numeric()
                            ->prefix('₹')
                            ->required()
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $commissionPercent = $get('commission_percentage') ?? 0;
                                $grossCommission = ($state * $commissionPercent) / 100;
                                $set('gross_commission', $grossCommission);
                                
                                $tdsPercent = $get('tds_percentage') ?? 0;
                                $tdsAmount = ($grossCommission * $tdsPercent) / 100;
                                $set('tds_amount', $tdsAmount);
                                $set('net_commission', $grossCommission - $tdsAmount);
                            })
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Commission Calculation')
                    ->schema([
                        Forms\Components\TextInput::make('commission_percentage')
                            ->label('Commission %')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->default(2)
                            ->required()
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $dealValue = $get('deal_value') ?? 0;
                                $grossCommission = ($dealValue * $state) / 100;
                                $set('gross_commission', $grossCommission);
                                
                                $tdsPercent = $get('tds_percentage') ?? 0;
                                $tdsAmount = ($grossCommission * $tdsPercent) / 100;
                                $set('tds_amount', $tdsAmount);
                                $set('net_commission', $grossCommission - $tdsAmount);
                            })
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('gross_commission')
                            ->label('Gross Commission')
                            ->numeric()
                            ->prefix('₹')
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('tds_percentage')
                            ->label('TDS %')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->default(5)
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $grossCommission = $get('gross_commission') ?? 0;
                                $tdsAmount = ($grossCommission * $state) / 100;
                                $set('tds_amount', $tdsAmount);
                                $set('net_commission', $grossCommission - $tdsAmount);
                            })
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('tds_amount')
                            ->label('TDS Amount')
                            ->numeric()
                            ->prefix('₹')
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('other_deductions')
                            ->label('Other Deductions')
                            ->numeric()
                            ->prefix('₹')
                            ->default(0)
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $grossCommission = $get('gross_commission') ?? 0;
                                $tdsAmount = $get('tds_amount') ?? 0;
                                $set('net_commission', $grossCommission - $tdsAmount - $state);
                            })
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('net_commission')
                            ->label('Net Commission')
                            ->numeric()
                            ->prefix('₹')
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Split & Payment')
                    ->schema([
                        Forms\Components\TextInput::make('split_with_agent_id')
                            ->label('Split With Agent ID')
                            ->numeric()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('split_percentage')
                            ->label('Split %')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->columnSpan(1),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'paid' => 'Paid',
                                'on_hold' => 'On Hold',
                            ])
                            ->default('pending')
                            ->required()
                            ->live()
                            ->columnSpan(1),
                        Forms\Components\DatePicker::make('payment_date')
                            ->label('Payment Date')
                            ->native(false)
                            ->visible(fn ($get) => $get('payment_status') === 'paid')
                            ->columnSpan(1),
                        Forms\Components\Select::make('approved_by')
                            ->label('Approved By')
                            ->relationship('approver', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn ($get) => in_array($get('payment_status'), ['approved', 'paid']))
                            ->columnSpan(1),
                        Forms\Components\DatePicker::make('approved_at')
                            ->label('Approved At')
                            ->native(false)
                            ->visible(fn ($get) => in_array($get('payment_status'), ['approved', 'paid']))
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('remarks')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('opportunity.opportunity_number')
                    ->label('Opp #')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('agent.name')
                    ->label('Agent')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('property.name')
                    ->label('Property')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('deal_value')
                    ->label('Deal Value')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('commission_percentage')
                    ->label('Rate')
                    ->suffix('%')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('gross_commission')
                    ->label('Gross')
                    ->money('INR')
                    ->sortable()
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('net_commission')
                    ->label('Net')
                    ->money('INR')
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->color('success'),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'approved',
                        'success' => 'paid',
                        'danger' => 'on_hold',
                    ])
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state))),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Paid On')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('agent_id')
                    ->label('Agent')
                    ->relationship('agent', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'paid' => 'Paid',
                        'on_hold' => 'On Hold',
                    ])
                    ->multiple(),
                Tables\Filters\Filter::make('high_value')
                    ->label('High Value (>1L)')
                    ->query(fn (EloquentBuilder $query): EloquentBuilder => $query->where('net_commission', '>', 100000)),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-badge')
                    ->color('info')
                    ->visible(fn ($record) => $record->payment_status === 'pending')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'payment_status' => 'approved',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);
                    }),
                Tables\Actions\Action::make('markPaid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn ($record) => $record->payment_status === 'approved')
                    ->form([
                        Forms\Components\DatePicker::make('payment_date')->default(today())->required(),
                        Forms\Components\Textarea::make('remarks'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update(array_merge($data, ['payment_status' => 'paid']));
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-badge')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->update([
                                'payment_status' => 'approved',
                                'approved_by' => auth()->id(),
                                'approved_at' => now(),
                            ]);
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommissions::route('/'),
            'create' => Pages\CreateCommission::route('/create'),
            'view' => Pages\ViewCommission::route('/{record}'),
            'edit' => Pages\EditCommission::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        try {
            return static::getModel()::where('payment_status', 'pending')->count();
        } catch (\Exception $e) {
            return null;
        }
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
