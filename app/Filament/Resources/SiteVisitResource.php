<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteVisitResource\Pages;
use App\Models\SiteVisit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SiteVisitResource extends Resource
{
    protected static ?string $model = SiteVisit::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    
    protected static ?string $navigationGroup = 'Sales Pipeline';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Site Visit Details')
                    ->schema([
                        Forms\Components\Select::make('lead_id')
                            ->label('Lead')
                            ->relationship('lead', 'full_name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Select::make('opportunity_id')
                            ->label('Opportunity')
                            ->relationship('opportunity', 'title')
                            ->searchable()
                            ->preload()
                            ->columnSpan(1),
                        Forms\Components\Select::make('property_id')
                            ->label('Property')
                            ->relationship('property', 'name')
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
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Schedule')
                    ->schema([
                        Forms\Components\DateTimePicker::make('scheduled_at')
                            ->label('Scheduled Date & Time')
                            ->required()
                            ->native(false)
                            ->seconds(false)
                            ->columnSpan(1),
                        Forms\Components\Select::make('status')
                            ->options([
                                'planned' => 'Planned',
                                'confirmed' => 'Confirmed',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'no_show' => 'No Show',
                            ])
                            ->default('planned')
                            ->required()
                            ->live()
                            ->columnSpan(1),
                        Forms\Components\DateTimePicker::make('actual_visit_at')
                            ->label('Actual Visit Time')
                            ->native(false)
                            ->seconds(false)
                            ->visible(fn ($get) => $get('status') === 'completed')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('duration_minutes')
                            ->label('Duration (minutes)')
                            ->numeric()
                            ->minValue(0)
                            ->visible(fn ($get) => $get('status') === 'completed')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Feedback')
                    ->schema([
                        Forms\Components\Select::make('customer_interest_level')
                            ->label('Interest Level')
                            ->options([
                                'very_high' => 'Very High',
                                'high' => 'High',
                                'medium' => 'Medium',
                                'low' => 'Low',
                                'not_interested' => 'Not Interested',
                            ])
                            ->visible(fn ($get) => $get('status') === 'completed')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('rating')
                            ->label('Customer Rating')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->suffix('/5')
                            ->visible(fn ($get) => $get('status') === 'completed')
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('customer_feedback')
                            ->label('Customer Feedback')
                            ->rows(3)
                            ->visible(fn ($get) => $get('status') === 'completed')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('agent_notes')
                            ->label('Agent Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn ($get) => in_array($get('status'), ['completed', 'cancelled', 'no_show'])),
                    
                Forms\Components\Section::make('Follow-up')
                    ->schema([
                        Forms\Components\Toggle::make('follow_up_required')
                            ->label('Follow-up Required')
                            ->default(true)
                            ->live()
                            ->columnSpan(1),
                        Forms\Components\DatePicker::make('follow_up_date')
                            ->label('Follow-up Date')
                            ->native(false)
                            ->visible(fn ($get) => $get('follow_up_required'))
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('follow_up_notes')
                            ->label('Follow-up Notes')
                            ->rows(2)
                            ->visible(fn ($get) => $get('follow_up_required'))
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
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Scheduled')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('lead.full_name')
                    ->label('Lead')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('property.name')
                    ->label('Property')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('assignedAgent.name')
                    ->label('Agent')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'planned',
                        'info' => 'confirmed',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                        'warning' => 'no_show',
                    ]),
                Tables\Columns\BadgeColumn::make('customer_interest_level')
                    ->label('Interest')
                    ->colors([
                        'success' => 'very_high',
                        'info' => 'high',
                        'warning' => 'medium',
                        'gray' => 'low',
                        'danger' => 'not_interested',
                    ])
                    ->formatStateUsing(fn ($state) => $state ? str_replace('_', ' ', ucwords($state)) : '-')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('rating')
                    ->suffix('/5')
                    ->color('warning')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('follow_up_required')
                    ->label('Follow-up')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'planned' => 'Planned',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'no_show' => 'No Show',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->label('Agent')
                    ->relationship('assignedAgent', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\Filter::make('upcoming')
                    ->label('Upcoming Visits')
                    ->query(fn (EloquentBuilder $query): EloquentBuilder => $query->where('scheduled_at', '>', now())->whereIn('status', ['planned', 'confirmed'])),
                Tables\Filters\Filter::make('today')
                    ->label('Today')
                    ->query(fn (EloquentBuilder $query): EloquentBuilder => $query->whereDate('scheduled_at', today())),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('markCompleted')
                    ->label('Complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => in_array($record->status, ['planned', 'confirmed']))
                    ->form([
                        Forms\Components\DateTimePicker::make('actual_visit_at')->default(now()),
                        Forms\Components\Select::make('customer_interest_level')
                            ->options([
                                'very_high' => 'Very High',
                                'high' => 'High',
                                'medium' => 'Medium',
                                'low' => 'Low',
                                'not_interested' => 'Not Interested',
                            ]),
                        Forms\Components\TextInput::make('rating')->numeric()->minValue(1)->maxValue(5),
                        Forms\Components\Textarea::make('customer_feedback'),
                        Forms\Components\Textarea::make('agent_notes'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update(array_merge($data, ['status' => 'completed']));
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('scheduled_at', 'desc');
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
            'index' => Pages\ListSiteVisits::route('/'),
            'create' => Pages\CreateSiteVisit::route('/create'),
            'view' => Pages\ViewSiteVisit::route('/{record}'),
            'edit' => Pages\EditSiteVisit::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        try {
            return static::getModel()::whereDate('scheduled_at', today())->whereIn('status', ['planned', 'confirmed'])->count();
        } catch (\Exception $e) {
            return null;
        }
    }
}
