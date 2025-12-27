<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Filament\Resources\LeadResource\RelationManagers;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationGroup = 'Sales Pipeline';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('full_name')
                    ->required(),
                Forms\Components\TextInput::make('mobile')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email(),
                Forms\Components\TextInput::make('alternate_mobile'),
                Forms\Components\TextInput::make('budget_min')
                    ->numeric(),
                Forms\Components\TextInput::make('budget_max')
                    ->numeric(),
                Forms\Components\Textarea::make('preferred_locations')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('property_types')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('purchase_intent')
                    ->required(),
                Forms\Components\Select::make('lead_source_id')
                    ->relationship('leadSource', 'name'),
                Forms\Components\Select::make('lead_status_id')
                    ->relationship('leadStatus', 'name'),
                Forms\Components\TextInput::make('assigned_to')
                    ->numeric(),
                Forms\Components\TextInput::make('priority')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('remarks')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('utm_source'),
                Forms\Components\TextInput::make('utm_medium'),
                Forms\Components\TextInput::make('utm_campaign'),
                Forms\Components\TextInput::make('utm_term'),
                Forms\Components\TextInput::make('utm_content'),
                Forms\Components\DateTimePicker::make('first_contact_at'),
                Forms\Components\DateTimePicker::make('last_contact_at'),
                Forms\Components\DateTimePicker::make('qualified_at'),
                Forms\Components\DateTimePicker::make('converted_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mobile')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alternate_mobile')
                    ->searchable(),
                Tables\Columns\TextColumn::make('budget_min')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('budget_max')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('purchase_intent')
                    ->searchable(),
                Tables\Columns\TextColumn::make('leadSource.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('leadStatus.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assigned_to')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->searchable(),
                Tables\Columns\TextColumn::make('utm_source')
                    ->searchable(),
                Tables\Columns\TextColumn::make('utm_medium')
                    ->searchable(),
                Tables\Columns\TextColumn::make('utm_campaign')
                    ->searchable(),
                Tables\Columns\TextColumn::make('utm_term')
                    ->searchable(),
                Tables\Columns\TextColumn::make('utm_content')
                    ->searchable(),
                Tables\Columns\TextColumn::make('first_contact_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_contact_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('qualified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('converted_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OpportunitiesRelationManager::class,
            RelationManagers\TasksRelationManager::class,
            RelationManagers\SiteVisitsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}
