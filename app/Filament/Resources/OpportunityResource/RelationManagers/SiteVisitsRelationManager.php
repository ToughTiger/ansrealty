<?php

namespace App\Filament\Resources\OpportunityResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SiteVisitsRelationManager extends RelationManager
{
    protected static string $relationship = 'siteVisits';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Site Visit Details')
                    ->schema([
                        Forms\Components\Select::make('property_id')
                            ->label('Property')
                            ->relationship('property', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('assigned_to')
                            ->label('Assigned Agent')
                            ->relationship('assignedAgent', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\DateTimePicker::make('scheduled_date')
                            ->label('Scheduled Date & Time')
                            ->required()
                            ->minDate(now()),

                        Forms\Components\Select::make('status')
                            ->options([
                                'Planned' => 'Planned',
                                'Confirmed' => 'Confirmed',
                                'Completed' => 'Completed',
                                'Cancelled' => 'Cancelled',
                                'Rescheduled' => 'Rescheduled',
                            ])
                            ->default('Planned')
                            ->required()
                            ->live(),
                    ])->columns(2),

                Forms\Components\Section::make('Feedback')
                    ->schema([
                        Forms\Components\Select::make('interest_level')
                            ->options([
                                'Very High' => 'Very High',
                                'High' => 'High',
                                'Medium' => 'Medium',
                                'Low' => 'Low',
                                'Not Interested' => 'Not Interested',
                            ])
                            ->visible(fn (Forms\Get $get) => $get('status') === 'Completed'),

                        Forms\Components\TextInput::make('rating')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->suffix('/ 5')
                            ->visible(fn (Forms\Get $get) => $get('status') === 'Completed'),

                        Forms\Components\Textarea::make('customer_feedback')
                            ->rows(3)
                            ->columnSpanFull()
                            ->visible(fn (Forms\Get $get) => $get('status') === 'Completed'),
                    ])->columns(2)
                    ->visible(fn (Forms\Get $get) => $get('status') === 'Completed'),

                Forms\Components\Section::make('Follow-up')
                    ->schema([
                        Forms\Components\Toggle::make('followup_required')
                            ->label('Follow-up Required')
                            ->live(),

                        Forms\Components\DatePicker::make('followup_date')
                            ->label('Follow-up Date')
                            ->visible(fn (Forms\Get $get) => $get('followup_required')),

                        Forms\Components\Textarea::make('notes')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('scheduled_date')
            ->columns([
                Tables\Columns\TextColumn::make('scheduled_date')
                    ->label('Scheduled')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('property.name')
                    ->label('Property')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('assignedAgent.name')
                    ->label('Agent')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'Planned',
                        'info' => 'Confirmed',
                        'success' => 'Completed',
                        'danger' => 'Cancelled',
                        'warning' => 'Rescheduled',
                    ]),

                Tables\Columns\BadgeColumn::make('interest_level')
                    ->label('Interest')
                    ->colors([
                        'success' => 'Very High',
                        'info' => 'High',
                        'warning' => 'Medium',
                        'secondary' => 'Low',
                        'danger' => 'Not Interested',
                    ])
                    ->visible(fn ($record) => $record->status === 'Completed'),

                Tables\Columns\TextColumn::make('rating')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' / 5' : '-')
                    ->visible(fn ($record) => $record->status === 'Completed'),

                Tables\Columns\IconColumn::make('followup_required')
                    ->label('Follow-up')
                    ->boolean(),

                Tables\Columns\TextColumn::make('followup_date')
                    ->label('Follow-up Date')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->options([
                        'Planned' => 'Planned',
                        'Confirmed' => 'Confirmed',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                        'Rescheduled' => 'Rescheduled',
                    ]),

                Tables\Filters\Filter::make('upcoming')
                    ->label('Upcoming Only')
                    ->query(fn ($query) => $query->where('scheduled_date', '>=', now())),

                Tables\Filters\Filter::make('today')
                    ->label('Today\'s Visits')
                    ->query(fn ($query) => $query->whereDate('scheduled_date', today())),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('markCompleted')
                    ->label('Complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => in_array($record->status, ['Planned', 'Confirmed']))
                    ->form([
                        Forms\Components\Select::make('interest_level')
                            ->options([
                                'Very High' => 'Very High',
                                'High' => 'High',
                                'Medium' => 'Medium',
                                'Low' => 'Low',
                                'Not Interested' => 'Not Interested',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('rating')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->suffix('/ 5'),

                        Forms\Components\Textarea::make('customer_feedback')
                            ->rows(3),

                        Forms\Components\Toggle::make('followup_required')
                            ->label('Follow-up Required')
                            ->live(),

                        Forms\Components\DatePicker::make('followup_date')
                            ->label('Follow-up Date')
                            ->visible(fn (Forms\Get $get) => $get('followup_required')),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'Completed',
                            'actual_visit_time' => now(),
                            'interest_level' => $data['interest_level'],
                            'rating' => $data['rating'] ?? null,
                            'customer_feedback' => $data['customer_feedback'] ?? null,
                            'followup_required' => $data['followup_required'],
                            'followup_date' => $data['followup_date'] ?? null,
                        ]);
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('scheduled_date', 'desc');
    }
}
