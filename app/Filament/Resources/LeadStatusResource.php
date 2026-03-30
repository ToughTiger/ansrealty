<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadStatusResource\Pages;
use App\Models\LeadStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeadStatusResource extends Resource
{
    protected static ?string $model = LeadStatus::class;
    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Lead Statuses';
    protected static ?string $pluralLabel = 'Lead Statuses';

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
                    ->default(fn () => LeadStatus::max('order') + 1),
                    
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
                    
                Forms\Components\Textarea::make('description')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                    
                Tables\Columns\ColorColumn::make('color')
                    ->label('Color'),
                    
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->badge()
                    ->color('info'),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                    
                Tables\Columns\TextColumn::make('leads_count')
                    ->counts('leads')
                    ->label('Leads')
                    ->badge()
                    ->color('success'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
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
            'index' => Pages\ListLeadStatuses::route('/'),
            'create' => Pages\CreateLeadStatus::route('/create'),
            'edit' => Pages\EditLeadStatus::route('/{record}/edit'),
        ];
    }
}
