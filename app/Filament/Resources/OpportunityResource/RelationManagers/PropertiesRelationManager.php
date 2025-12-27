<?php

namespace App\Filament\Resources\OpportunityResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PropertiesRelationManager extends RelationManager
{
    protected static string $relationship = 'properties';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('property_id')
                    ->label('Property')
                    ->relationship('property', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Image')
                    ->circular()
                    ->defaultImageUrl(url('/images/property-placeholder.png')),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('builder.company_name')
                    ->label('Builder')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('property_type')
                    ->label('Type')
                    ->colors([
                        'success' => 'Residential Flat',
                        'info' => 'Villa',
                        'warning' => 'Plot',
                        'primary' => 'Commercial',
                    ]),

                Tables\Columns\TextColumn::make('configuration')
                    ->label('Config')
                    ->formatStateUsing(fn ($record) => 
                        ($record->bhk ? $record->bhk . ' BHK' : '') . 
                        ($record->bathrooms ? ' | ' . $record->bathrooms . ' Bath' : '')
                    ),

                Tables\Columns\TextColumn::make('price_range')
                    ->label('Price')
                    ->formatStateUsing(fn ($record) => 
                        '₹' . number_format($record->price_min / 100000, 2) . 'L - ₹' . number_format($record->price_max / 100000, 2) . 'L'
                    ),

                Tables\Columns\TextColumn::make('city')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('availability_status')
                    ->label('Status')
                    ->colors([
                        'success' => 'Available',
                        'warning' => 'Under Construction',
                        'danger' => 'Sold Out',
                        'secondary' => 'Booked',
                    ]),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('property_type')
                    ->options([
                        'Residential Flat' => 'Residential Flat',
                        'Villa' => 'Villa',
                        'Plot' => 'Plot',
                        'Commercial' => 'Commercial',
                        'Farmhouse' => 'Farmhouse',
                    ]),

                Tables\Filters\SelectFilter::make('availability_status')
                    ->options([
                        'Available' => 'Available',
                        'Under Construction' => 'Under Construction',
                        'Sold Out' => 'Sold Out',
                        'Booked' => 'Booked',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
