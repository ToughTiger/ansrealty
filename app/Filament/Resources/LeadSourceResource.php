<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadSourceResource\Pages;
use App\Models\LeadSource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeadSourceResource extends Resource
{
    protected static ?string $model = LeadSource::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Lead Sources';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', \Str::slug($state))),
                    
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                    
                Forms\Components\ColorPicker::make('color')
                    ->default('#3b82f6'),
                    
                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->default(fn () => LeadSource::max('order') + 1),
                    
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('#')
                    ->sortable(),
                    
                Tables\Columns\ColorColumn::make('color')
                    ->label('Color'),
                    
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->badge()
                    ->color('gray'),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeadSources::route('/'),
            'create' => Pages\CreateLeadSource::route('/create'),
            'edit' => Pages\EditLeadSource::route('/{record}/edit'),
        ];
    }
}
