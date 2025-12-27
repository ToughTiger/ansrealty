<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OpportunityResource\Pages;
use App\Filament\Resources\OpportunityResource\RelationManagers;
use App\Models\Opportunity;
use App\Models\OpportunityStage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OpportunityResource extends Resource
{
    protected static ?string $model = Opportunity::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    
    protected static ?string $navigationGroup = 'Sales Pipeline';
    
    protected static ?int $navigationSort = 2;
    
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Opportunity Information')
                    ->schema([
                        Forms\Components\TextInput::make('opportunity_number')
                            ->label('Opportunity #')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn ($record) => $record !== null),
                        Forms\Components\Select::make('lead_id')
                            ->relationship('lead', 'full_name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Select::make('assigned_to')
                            ->label('Assigned Agent')
                            ->relationship('assignedAgent', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->placeholder('e.g., 3 BHK Flat Purchase at Green Valley'),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Stage & Value')
                    ->schema([
                        Forms\Components\Select::make('opportunity_stage_id')
                            ->label('Stage')
                            ->relationship('opportunityStage', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('probability')
                            ->label('Probability %')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(10)
                            ->suffix('%')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('expected_value')
                            ->label('Expected Deal Value')
                            ->numeric()
                            ->prefix('₹')
                            ->columnSpan(1),
                        Forms\Components\DatePicker::make('expected_close_date')
                            ->label('Expected Closing Date')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Closure Details')
                    ->schema([
                        Forms\Components\Select::make('close_status')
                            ->options([
                                'open' => 'Open',
                                'won' => 'Closed Won',
                                'lost' => 'Closed Lost',
                            ])
                            ->default('open')
                            ->required()
                            ->live()
                            ->columnSpan(1),
                        Forms\Components\DatePicker::make('actual_close_date')
                            ->label('Actual Close Date')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->visible(fn ($get) => in_array($get('close_status'), ['won', 'lost']))
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('final_value')
                            ->label('Final Deal Value')
                            ->numeric()
                            ->prefix('₹')
                            ->visible(fn ($get) => $get('close_status') === 'won')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('lost_reason')
                            ->label('Lost Reason')
                            ->visible(fn ($get) => $get('close_status') === 'lost')
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('lost_remarks')
                            ->label('Remarks')
                            ->rows(2)
                            ->visible(fn ($get) => $get('close_status') === 'lost')
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
                Tables\Columns\TextColumn::make('opportunity_number')
                    ->label('Opp #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 30 ? $state : null;
                    }),
                Tables\Columns\TextColumn::make('lead.full_name')
                    ->label('Lead')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedAgent.name')
                    ->label('Agent')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('opportunityStage.name')
                    ->label('Stage')
                    ->colors([
                        'gray' => 'New',
                        'info' => 'Contacted',
                        'warning' => 'Qualified',
                        'primary' => 'Proposal',
                        'success' => 'Negotiation',
                        'danger' => 'Lost',
                    ]),
                Tables\Columns\TextColumn::make('probability')
                    ->label('Prob.')
                    ->suffix('%')
                    ->sortable()
                    ->alignCenter()
                    ->color(fn ($state) => $state >= 70 ? 'success' : ($state >= 40 ? 'warning' : 'gray')),
                Tables\Columns\TextColumn::make('expected_value')
                    ->label('Value')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('expected_close_date')
                    ->label('Close Date')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($state) => $state && $state->isPast() ? 'danger' : 'gray'),
                Tables\Columns\BadgeColumn::make('close_status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'open',
                        'success' => 'won',
                        'danger' => 'lost',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('opportunity_stage_id')
                    ->label('Stage')
                    ->relationship('opportunityStage', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->label('Agent')
                    ->relationship('assignedAgent', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\SelectFilter::make('close_status')
                    ->label('Status')
                    ->options([
                        'open' => 'Open',
                        'won' => 'Won',
                        'lost' => 'Lost',
                    ])
                    ->multiple(),
                Tables\Filters\Filter::make('overdue')
                    ->label('Overdue')
                    ->query(fn (EloquentBuilder $query): EloquentBuilder => $query->where('expected_close_date', '<', now())->where('close_status', 'open')),
                Tables\Filters\Filter::make('high_value')
                    ->label('High Value (>50L)')
                    ->query(fn (EloquentBuilder $query): EloquentBuilder => $query->where('expected_value', '>', 5000000)),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('markWon')
                    ->label('Mark Won')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->close_status === 'open')
                    ->action(fn ($record) => $record->update([
                        'close_status' => 'won',
                        'actual_close_date' => now(),
                    ])),
                Tables\Actions\Action::make('markLost')
                    ->label('Mark Lost')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->close_status === 'open')
                    ->form([
                        Forms\Components\TextInput::make('lost_reason')->required(),
                        Forms\Components\Textarea::make('lost_remarks'),
                    ])
                    ->action(fn ($record, array $data) => $record->update([
                        'close_status' => 'lost',
                        'actual_close_date' => now(),
                        'lost_reason' => $data['lost_reason'],
                        'lost_remarks' => $data['lost_remarks'] ?? null,
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('assignAgent')
                        ->label('Assign to Agent')
                        ->icon('heroicon-o-user')
                        ->form([
                            Forms\Components\Select::make('assigned_to')
                                ->label('Agent')
                                ->relationship('assignedAgent', 'name')
                                ->required(),
                        ])
                        ->action(fn ($records, array $data) => $records->each->update(['assigned_to' => $data['assigned_to']])),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PropertiesRelationManager::class,
            RelationManagers\SiteVisitsRelationManager::class,
            RelationManagers\TasksRelationManager::class,
            RelationManagers\NegotiationsRelationManager::class,
            RelationManagers\CommissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOpportunities::route('/'),
            'create' => Pages\CreateOpportunity::route('/create'),
            'view' => Pages\ViewOpportunity::route('/{record}'),
            'edit' => Pages\EditOpportunity::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        try {
            return static::getModel()::where('close_status', 'open')->count();
        } catch (\Exception $e) {
            return null;
        }
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
