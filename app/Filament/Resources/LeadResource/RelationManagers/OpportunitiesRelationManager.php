<?php

namespace App\Filament\Resources\LeadResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OpportunitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'opportunities';

    protected static ?string $recordTitleAttribute = 'opportunity_number';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('opportunity_number')
                    ->required()
                    ->maxLength(255)
                    ->disabled(),
                
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\Select::make('opportunity_stage_id')
                    ->relationship('opportunityStage', 'name')
                    ->required(),
                
                Forms\Components\TextInput::make('expected_value')
                    ->numeric()
                    ->prefix('₹'),
                
                Forms\Components\DatePicker::make('expected_close_date'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('opportunity_number')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('opportunityStage.name')
                    ->label('Stage'),
                
                Tables\Columns\TextColumn::make('expected_value_formatted')
                    ->label('Expected Value'),
                
                Tables\Columns\BadgeColumn::make('close_status')
                    ->colors([
                        'warning' => 'Open',
                        'success' => 'Won',
                        'danger' => 'Lost',
                    ]),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
