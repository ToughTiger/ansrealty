<?php

namespace App\Filament\Resources\OpportunityResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Task Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->placeholder('e.g., Follow-up call with customer'),

                        Forms\Components\Select::make('task_type')
                            ->label('Type')
                            ->options([
                                'Call' => 'Call',
                                'Email' => 'Email',
                                'Meeting' => 'Meeting',
                                'Site Visit' => 'Site Visit',
                                'WhatsApp' => 'WhatsApp',
                                'Document Collection' => 'Document Collection',
                                'Follow-up' => 'Follow-up',
                                'Other' => 'Other',
                            ])
                            ->required()
                            ->default('Call'),

                        Forms\Components\Select::make('priority')
                            ->options([
                                'Low' => 'Low',
                                'Normal' => 'Normal',
                                'High' => 'High',
                                'Urgent' => 'Urgent',
                            ])
                            ->default('Normal')
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Assignment & Timeline')
                    ->schema([
                        Forms\Components\Select::make('assigned_to')
                            ->label('Assigned Agent')
                            ->relationship('assignedAgent', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'Pending' => 'Pending',
                                'In Progress' => 'In Progress',
                                'Completed' => 'Completed',
                                'Cancelled' => 'Cancelled',
                            ])
                            ->default('Pending')
                            ->required(),

                        Forms\Components\DateTimePicker::make('due_date')
                            ->label('Due Date')
                            ->required()
                            ->minDate(now()),

                        Forms\Components\DateTimePicker::make('completed_at')
                            ->label('Completed At')
                            ->disabled()
                            ->visible(fn ($record) => $record?->status === 'Completed'),
                    ])->columns(2),

                Forms\Components\Section::make('Results')
                    ->schema([
                        Forms\Components\Select::make('outcome')
                            ->options([
                                'Successful' => 'Successful',
                                'Not Reachable' => 'Not Reachable',
                                'Rescheduled' => 'Rescheduled',
                                'Not Interested' => 'Not Interested',
                                'Call Back Later' => 'Call Back Later',
                            ])
                            ->visible(fn ($record) => $record?->status === 'Completed'),

                        Forms\Components\Textarea::make('remarks')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2)
                    ->visible(fn ($record) => $record?->status === 'Completed'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->weight('bold'),

                Tables\Columns\BadgeColumn::make('task_type')
                    ->label('Type')
                    ->colors([
                        'success' => 'Call',
                        'info' => 'Email',
                        'warning' => 'Meeting',
                        'primary' => 'Site Visit',
                        'secondary' => ['WhatsApp', 'Document Collection', 'Follow-up', 'Other'],
                    ]),

                Tables\Columns\BadgeColumn::make('priority')
                    ->colors([
                        'secondary' => 'Low',
                        'info' => 'Normal',
                        'warning' => 'High',
                        'danger' => 'Urgent',
                    ]),

                Tables\Columns\TextColumn::make('assignedAgent.name')
                    ->label('Agent')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Due Date')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->color(fn ($record) => $record->due_date < now() && $record->status !== 'Completed' ? 'danger' : null)
                    ->weight(fn ($record) => $record->due_date < now() && $record->status !== 'Completed' ? 'bold' : null),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'Pending',
                        'info' => 'In Progress',
                        'success' => 'Completed',
                        'danger' => 'Cancelled',
                    ]),

                Tables\Columns\TextColumn::make('outcome')
                    ->visible(fn ($record) => $record->status === 'Completed'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('task_type')
                    ->label('Type')
                    ->multiple()
                    ->options([
                        'Call' => 'Call',
                        'Email' => 'Email',
                        'Meeting' => 'Meeting',
                        'Site Visit' => 'Site Visit',
                        'WhatsApp' => 'WhatsApp',
                        'Document Collection' => 'Document Collection',
                        'Follow-up' => 'Follow-up',
                        'Other' => 'Other',
                    ]),

                Tables\Filters\SelectFilter::make('priority')
                    ->multiple()
                    ->options([
                        'Low' => 'Low',
                        'Normal' => 'Normal',
                        'High' => 'High',
                        'Urgent' => 'Urgent',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->options([
                        'Pending' => 'Pending',
                        'In Progress' => 'In Progress',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\Filter::make('overdue')
                    ->label('Overdue')
                    ->query(fn ($query) => $query->where('due_date', '<', now())->where('status', '!=', 'Completed')),

                Tables\Filters\Filter::make('due_today')
                    ->label('Due Today')
                    ->query(fn ($query) => $query->whereDate('due_date', today())),

                Tables\Filters\Filter::make('high_priority')
                    ->label('High Priority')
                    ->query(fn ($query) => $query->whereIn('priority', ['High', 'Urgent'])),
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
                    ->visible(fn ($record) => $record->status !== 'Completed')
                    ->form([
                        Forms\Components\Select::make('outcome')
                            ->options([
                                'Successful' => 'Successful',
                                'Not Reachable' => 'Not Reachable',
                                'Rescheduled' => 'Rescheduled',
                                'Not Interested' => 'Not Interested',
                                'Call Back Later' => 'Call Back Later',
                            ])
                            ->required(),

                        Forms\Components\Textarea::make('remarks')
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'Completed',
                            'completed_at' => now(),
                            'outcome' => $data['outcome'],
                            'remarks' => $data['remarks'] ?? null,
                        ]);
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('markCompleted')
                        ->label('Mark Completed')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update([
                            'status' => 'Completed',
                            'completed_at' => now(),
                        ])),
                ]),
            ])
            ->defaultSort('due_date', 'asc');
    }
}
