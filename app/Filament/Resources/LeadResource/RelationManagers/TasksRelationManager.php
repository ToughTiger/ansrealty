<?php

namespace App\Filament\Resources\LeadResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\Textarea::make('description')
                    ->rows(3),
                
                Forms\Components\Select::make('type')
                    ->options([
                        'Call' => 'Call',
                        'Email' => 'Email',
                        'Meeting' => 'Meeting',
                        'Site Visit' => 'Site Visit',
                        'WhatsApp' => 'WhatsApp',
                        'Follow Up' => 'Follow Up',
                        'Other' => 'Other',
                    ])
                    ->required(),
                
                Forms\Components\Select::make('priority')
                    ->options([
                        'Low' => 'Low',
                        'Medium' => 'Medium',
                        'High' => 'High',
                        'Urgent' => 'Urgent',
                    ])
                    ->required(),
                
                Forms\Components\Select::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'In Progress' => 'In Progress',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->required(),
                
                Forms\Components\DateTimePicker::make('due_date')
                    ->required(),
                
                Forms\Components\Select::make('assigned_to')
                    ->relationship('assignedAgent', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('type')
                    ->icon(fn ($state) => match($state) {
                        'Call' => 'heroicon-o-phone',
                        'Email' => 'heroicon-o-envelope',
                        'Meeting' => 'heroicon-o-calendar',
                        'WhatsApp' => 'heroicon-o-chat-bubble-left-right',
                        default => 'heroicon-o-clipboard-document-list',
                    }),
                
                Tables\Columns\BadgeColumn::make('priority')
                    ->colors([
                        'danger' => 'Urgent',
                        'warning' => 'High',
                        'primary' => 'Medium',
                        'success' => 'Low',
                    ]),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'Pending',
                        'primary' => 'In Progress',
                        'success' => 'Completed',
                        'danger' => 'Cancelled',
                    ]),
                
                Tables\Columns\TextColumn::make('due_date')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('assignedAgent.name')
                    ->label('Assigned To'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'In Progress' => 'In Progress',
                        'Completed' => 'Completed',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('due_date', 'asc');
    }
}
