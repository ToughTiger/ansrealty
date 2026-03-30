<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssignmentRuleResource\Pages;
use App\Models\AssignmentRule;
use App\Models\LeadSource;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AssignmentRuleResource extends Resource
{
    protected static ?string $model = AssignmentRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    
    protected static ?string $navigationGroup = 'Settings';
    
    protected static ?int $navigationSort = 98;
    
    protected static ?string $navigationLabel = 'Assignment Rules';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Rule Configuration')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Round Robin - Sales Team')
                            ->columnSpan(2),
                        
                        Forms\Components\Select::make('type')
                            ->label('Assignment Type')
                            ->options([
                                'round_robin' => '🔄 Round Robin (Rotate between agents)',
                                'load_balance' => '⚖️ Load Balance (Least busy agent)',
                                'location' => '📍 Location-Based',
                                'source' => '📊 Source-Based',
                                'priority' => '🔥 Priority-Based',
                            ])
                            ->required()
                            ->default('round_robin')
                            ->reactive()
                            ->columnSpan(1),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Enable/disable this rule')
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('priority_order')
                            ->label('Priority Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower number = higher priority. Rules execute in order.')
                            ->columnSpan(1),
                        
                        Forms\Components\Textarea::make('description')
                            ->rows(2)
                            ->placeholder('Describe when and how this rule should be used')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Assignment Configuration')
                    ->schema([
                        Forms\Components\Select::make('assigned_users')
                            ->label('Assign To (Users/Agents)')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(User::pluck('name', 'id'))
                            ->required()
                            ->helperText('Select users who will receive leads via this rule')
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Conditions (Optional)')
                    ->description('Define when this rule should trigger')
                    ->schema([
                        Forms\Components\Select::make('conditions.sources')
                            ->label('Lead Sources')
                            ->multiple()
                            ->searchable()
                            ->options(LeadSource::pluck('name', 'id'))
                            ->helperText('Leave empty to apply to all sources')
                            ->visible(fn ($get) => in_array($get('type'), ['source', 'round_robin', 'load_balance']))
                            ->columnSpan(1),
                        
                        Forms\Components\TagsInput::make('conditions.locations')
                            ->label('Locations')
                            ->placeholder('Enter location names')
                            ->helperText('Rule applies only if lead prefers these locations')
                            ->visible(fn ($get) => in_array($get('type'), ['location', 'round_robin', 'load_balance']))
                            ->columnSpan(1),
                        
                        Forms\Components\Select::make('conditions.priorities')
                            ->label('Lead Priorities')
                            ->multiple()
                            ->options([
                                'Hot' => 'Hot',
                                'Warm' => 'Warm',
                                'Cold' => 'Cold',
                            ])
                            ->helperText('Leave empty to apply to all priorities')
                            ->visible(fn ($get) => in_array($get('type'), ['priority', 'round_robin', 'load_balance']))
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'round_robin',
                        'success' => 'load_balance',
                        'warning' => 'location',
                        'info' => 'source',
                        'danger' => 'priority',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'round_robin' => '🔄 Round Robin',
                        'load_balance' => '⚖️ Load Balance',
                        'location' => '📍 Location',
                        'source' => '📊 Source',
                        'priority' => '🔥 Priority',
                        default => $state,
                    }),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('priority_order')
                    ->label('Priority')
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('assigned_users')
                    ->label('Agents')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '—';
                        $count = count($state);
                        return $count . ' agent' . ($count !== 1 ? 's' : '');
                    })
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('counter.assignment_count')
                    ->label('Assignments')
                    ->default(0)
                    ->alignCenter()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('counter.last_assigned_at')
                    ->label('Last Used')
                    ->dateTime('d M, H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'round_robin' => 'Round Robin',
                        'load_balance' => 'Load Balance',
                        'location' => 'Location-Based',
                        'source' => 'Source-Based',
                        'priority' => 'Priority-Based',
                    ])
                    ->multiple(),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All rules')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\Action::make('test')
                    ->label('Test')
                    ->icon('heroicon-o-beaker')
                    ->color('warning')
                    ->action(function (AssignmentRule $record) {
                        $service = app(\App\Services\LeadAssignmentService::class);
                        // Create a dummy lead for testing
                        $testLead = new \App\Models\Lead([
                            'full_name' => 'Test Lead',
                            'mobile' => '9999999999',
                            'priority' => 'Warm',
                        ]);
                        
                        if ($service->ruleApplies($record, $testLead)) {
                            $user = $service->executeRule($record, $testLead);
                            if ($user) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Rule works!')
                                    ->body("Would assign to: {$user->name}")
                                    ->success()
                                    ->send();
                            } else {
                                \Filament\Notifications\Notification::make()
                                    ->title('No assignment')
                                    ->body('No eligible user found')
                                    ->warning()
                                    ->send();
                            }
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Rule does not apply')
                                ->body('Test lead does not match rule conditions')
                                ->warning()
                                ->send();
                        }
                    }),
                
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('priority_order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssignmentRules::route('/'),
            'create' => Pages\CreateAssignmentRule::route('/create'),
            'edit' => Pages\EditAssignmentRule::route('/{record}/edit'),
        ];
    }
}
