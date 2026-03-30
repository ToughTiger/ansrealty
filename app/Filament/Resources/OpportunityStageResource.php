<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OpportunityStageResource\Pages;
use App\Models\OpportunityStage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OpportunityStageResource extends Resource
{
    protected static ?string $model = OpportunityStage::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Opportunity Stages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Stage Information')
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
                    ])->columns(3),
                    
                Forms\Components\Section::make('Stage Settings')
                    ->schema([
                        Forms\Components\TextInput::make('probability')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->helperText('Win probability at this stage (0-100%)'),
                            
                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->required()
                            ->default(fn () => OpportunityStage::max('order') + 1)
                            ->helperText('Display order in dropdowns'),
                            
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Only active stages appear in dropdowns'),
                    ])->columns(3),
                    
                Forms\Components\Textarea::make('description')
                    ->maxLength(500)
                    ->columnSpanFull()
                    ->helperText('Internal description of this stage'),
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
                    ->size('lg')
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
                    
                Tables\Columns\TextColumn::make('probability')
                    ->label('Win Probability')
                    ->suffix('%')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state >= 80 => 'success',
                        $state >= 50 => 'warning',
                        $state >= 20 => 'info',
                        default => 'danger',
                    }),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                    
                Tables\Columns\TextColumn::make('opportunities_count')
                    ->counts('opportunities')
                    ->label('Active Opps')
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
            'index' => Pages\ListOpportunityStages::route('/'),
            'create' => Pages\CreateOpportunityStage::route('/create'),
            'edit' => Pages\EditOpportunityStage::route('/{record}/edit'),
        ];
    }
}
