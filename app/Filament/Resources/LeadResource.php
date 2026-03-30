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
                Forms\Components\Section::make('Basic Information')
                    ->description('Primary contact details')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\TextInput::make('full_name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('John Doe')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('mobile')
                            ->label('Mobile Number')
                            ->required()
                            ->tel()
                            ->maxLength(15)
                            ->placeholder('+91 9876543210')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('john@example.com')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('alternate_mobile')
                            ->label('Alternate Mobile')
                            ->tel()
                            ->maxLength(15)
                            ->placeholder('+91 9876543210')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
                
                Forms\Components\Section::make('Property Requirements')
                    ->description('What is the customer looking for?')
                    ->icon('heroicon-o-home-modern')
                    ->schema([
                        Forms\Components\Select::make('purchase_intent')
                            ->label('Purchase Intent')
                            ->options([
                                'Buy' => 'Buy',
                                'Rent' => 'Rent',
                                'Invest' => 'Invest',
                            ])
                            ->required()
                            ->default('Buy')
                            ->columnSpan(1),
                        Forms\Components\Select::make('priority')
                            ->label('Lead Priority')
                            ->options([
                                'Hot' => '🔥 Hot',
                                'Warm' => '⚡ Warm',
                                'Cold' => '❄️ Cold',
                            ])
                            ->required()
                            ->default('Warm')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('budget_min')
                            ->label('Budget Min')
                            ->numeric()
                            ->prefix('₹')
                            ->placeholder('5000000')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('budget_max')
                            ->label('Budget Max')
                            ->numeric()
                            ->prefix('₹')
                            ->placeholder('10000000')
                            ->columnSpan(1),
                        Forms\Components\TagsInput::make('preferred_locations')
                            ->label('Preferred Locations')
                            ->placeholder('Add locations (e.g., Andheri, Bandra)')
                            ->helperText('Press Enter after each location')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('property_types')
                            ->label('Property Types')
                            ->multiple()
                            ->options([
                                'Flat' => 'Flat / Apartment',
                                'Villa' => 'Villa / Bungalow',
                                'Plot' => 'Plot / Land',
                                'Commercial' => 'Commercial',
                                'Office' => 'Office Space',
                                'Shop' => 'Shop / Retail',
                                'Warehouse' => 'Warehouse',
                                'Penthouse' => 'Penthouse',
                            ])
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),
                
                Forms\Components\Section::make('Lead Assignment & Status')
                    ->description('Assign lead to agent and set status')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        Forms\Components\Select::make('lead_source_id')
                            ->label('Lead Source')
                            ->relationship('leadSource', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\ColorPicker::make('color')
                                    ->default('#3b82f6'),
                            ])
                            ->columnSpan(1),
                        Forms\Components\Select::make('lead_status_id')
                            ->label('Lead Status')
                            ->relationship('leadStatus', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->default(fn () => \App\Models\LeadStatus::where('name', 'New')->first()?->id)
                            ->columnSpan(1),
                        Forms\Components\Select::make('assigned_to')
                            ->label('Assign To')
                            ->relationship('assignedAgent', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Select an agent')
                            ->helperText('Leave empty for unassigned')
                            ->columnSpan(2),
                    ])
                    ->columns(2)
                    ->collapsible(),
                
                Forms\Components\Section::make('Notes & Remarks')
                    ->description('Additional information')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->placeholder('Any specific requirements or notes...')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('remarks')
                            ->label('Internal Remarks')
                            ->placeholder('Internal notes for team...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
                
                Forms\Components\Section::make('Tracking & Campaign Data')
                    ->description('UTM parameters and contact tracking')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Forms\Components\TextInput::make('utm_source')
                            ->label('UTM Source')
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('utm_medium')
                            ->label('UTM Medium')
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('utm_campaign')
                            ->label('UTM Campaign')
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('utm_term')
                            ->label('UTM Term')
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('utm_content')
                            ->label('UTM Content')
                            ->maxLength(255)
                            ->columnSpan(2),
                        Forms\Components\DateTimePicker::make('first_contact_at')
                            ->label('First Contact Date')
                            ->columnSpan(1),
                        Forms\Components\DateTimePicker::make('last_contact_at')
                            ->label('Last Contact Date')
                            ->columnSpan(1),
                        Forms\Components\DateTimePicker::make('qualified_at')
                            ->label('Qualified Date')
                            ->columnSpan(1),
                        Forms\Components\DateTimePicker::make('converted_at')
                            ->label('Converted Date')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mobile')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\BadgeColumn::make('priority')
                    ->colors([
                        'danger' => 'Hot',
                        'warning' => 'Warm',
                        'success' => 'Cold',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('leadSource.name')
                    ->badge()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('leadStatus.name')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedAgent.name')
                    ->label('Agent')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('budget_range')
                    ->label('Budget')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('purchase_intent')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->label('Created'),
                Tables\Columns\IconColumn::make('is_converted')
                    ->boolean()
                    ->label('Converted')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'Hot' => 'Hot',
                        'Warm' => 'Warm',
                        'Cold' => 'Cold',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('lead_source_id')
                    ->relationship('leadSource', 'name')
                    ->label('Source')
                    ->multiple(),
                Tables\Filters\SelectFilter::make('lead_status_id')
                    ->relationship('leadStatus', 'name')
                    ->label('Status')
                    ->multiple(),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->relationship('assignedAgent', 'name')
                    ->label('Assigned Agent')
                    ->multiple(),
                Tables\Filters\Filter::make('unassigned')
                    ->query(fn ($query) => $query->whereNull('assigned_to'))
                    ->label('Unassigned Only'),
                Tables\Filters\Filter::make('converted')
                    ->query(fn ($query) => $query->whereNotNull('converted_at'))
                    ->label('Converted Only'),
                Tables\Filters\Filter::make('not_converted')
                    ->query(fn ($query) => $query->whereNull('converted_at'))
                    ->label('Not Converted'),
                Tables\Filters\Filter::make('created_this_month')
                    ->query(fn ($query) => $query->whereMonth('created_at', now()->month))
                    ->label('This Month'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('convert')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('success')
                    ->url(fn (Lead $record) => route('filament.admin.resources.opportunities.create', ['lead_id' => $record->id]))
                    ->hidden(fn (Lead $record) => $record->is_converted),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('assign')
                        ->label('Assign Agent')
                        ->icon('heroicon-o-user-plus')
                        ->form([
                            Forms\Components\Select::make('assigned_to')
                                ->label('Agent')
                                ->relationship('assignedAgent', 'name')
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                $record->update(['assigned_to' => $data['assigned_to']]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('update_priority')
                        ->label('Update Priority')
                        ->icon('heroicon-o-flag')
                        ->form([
                            Forms\Components\Select::make('priority')
                                ->options([
                                    'Hot' => 'Hot',
                                    'Warm' => 'Warm',
                                    'Cold' => 'Cold',
                                ])
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                $record->update(['priority' => $data['priority']]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('update_status')
                        ->label('Update Status')
                        ->icon('heroicon-o-check-circle')
                        ->form([
                            Forms\Components\Select::make('lead_status_id')
                                ->label('Status')
                                ->relationship('leadStatus', 'name')
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                $record->update(['lead_status_id' => $data['lead_status_id']]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
