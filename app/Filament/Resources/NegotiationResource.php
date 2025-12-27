<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NegotiationResource\Pages;
use App\Models\Negotiation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class NegotiationResource extends Resource
{
    protected static ?string $model = Negotiation::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';
    
    protected static ?string $navigationGroup = 'Finance';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Negotiation Details')
                    ->schema([
                        Forms\Components\Select::make('opportunity_id')
                            ->relationship('opportunity', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('property_id')
                            ->relationship('property', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('property_listed_price')
                            ->label('Listed Price')
                            ->numeric()
                            ->prefix('₹')
                            ->required(),
                        Forms\Components\TextInput::make('customer_offer_price')
                            ->label('Customer Offer')
                            ->numeric()
                            ->prefix('₹')
                            ->required(),
                        Forms\Components\TextInput::make('counter_offer_price')
                            ->label('Counter Offer')
                            ->numeric()
                            ->prefix('₹'),
                        Forms\Components\TextInput::make('final_agreed_price')
                            ->label('Final Price')
                            ->numeric()
                            ->prefix('₹'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'initiated' => 'Initiated',
                                'in_progress' => 'In Progress',
                                'agreed' => 'Agreed',
                                'rejected' => 'Rejected',
                            ])
                            ->default('initiated')
                            ->required(),
                        Forms\Components\Textarea::make('remarks')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Approval')
                    ->schema([
                        Forms\Components\Select::make('approved_by')
                            ->relationship('approver', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\DateTimePicker::make('approved_at')
                            ->native(false),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        // Protect against missing table
        try {
            if (!\Schema::hasTable('negotiations')) {
                return $table->columns([])->emptyStateHeading('Please run migrations first');
            }
        } catch (\Exception $e) {
            return $table->columns([])->emptyStateHeading('Database not ready');
        }
        
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('opportunity.title')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('property.name')
                    ->searchable()
                    ->limit(25),
                Tables\Columns\TextColumn::make('customer_offer_price')
                    ->label('Offer')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('counter_offer_price')
                    ->label('Counter')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('final_agreed_price')
                    ->label('Agreed')
                    ->money('INR')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'initiated',
                        'info' => 'in_progress',
                        'success' => 'agreed',
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'initiated' => 'Initiated',
                        'in_progress' => 'In Progress',
                        'agreed' => 'Agreed',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageNegotiations::route('/'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        try {
            // Check if table exists first
            if (!\Schema::hasTable('negotiations')) {
                return null;
            }
            return static::getModel()::where('status', 'in_progress')->count();
        } catch (\Exception $e) {
            return null;
        }
    }
}
