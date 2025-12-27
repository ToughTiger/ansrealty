<?php

namespace App\Filament\Resources\LeadResource\RelationManagers;

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
                Forms\Components\Select::make('property_id')
                    ->relationship('property', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                
                Forms\Components\DateTimePicker::make('scheduled_at')
                    ->required(),
                
                Forms\Components\Select::make('status')
                    ->options([
                        'Planned' => 'Planned',
                        'Confirmed' => 'Confirmed',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                        'No Show' => 'No Show',
                    ])
                    ->required(),
                
                Forms\Components\Select::make('assigned_to')
                    ->relationship('assignedAgent', 'name')
                    ->searchable()
                    ->preload(),
                
                Forms\Components\Textarea::make('agent_notes')
                    ->rows(3),
                
                Forms\Components\Textarea::make('customer_feedback')
                    ->rows(3),
                
                Forms\Components\Select::make('customer_rating')
                    ->options([
                        1 => '⭐',
                        2 => '⭐⭐',
                        3 => '⭐⭐⭐',
                        4 => '⭐⭐⭐⭐',
                        5 => '⭐⭐⭐⭐⭐',
                    ]),
                
                Forms\Components\Toggle::make('follow_up_required'),
                
                Forms\Components\DatePicker::make('follow_up_date'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('property.name')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'Planned',
                        'success' => 'Confirmed',
                        'info' => 'Completed',
                        'danger' => 'Cancelled',
                        'warning' => 'No Show',
                    ]),
                
                Tables\Columns\TextColumn::make('assignedAgent.name')
                    ->label('Agent'),
                
                Tables\Columns\TextColumn::make('customer_rating')
                    ->formatStateUsing(fn ($state) => $state ? str_repeat('⭐', $state) : '-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Planned' => 'Planned',
                        'Confirmed' => 'Confirmed',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
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
            ->defaultSort('scheduled_at', 'desc');
    }
}
