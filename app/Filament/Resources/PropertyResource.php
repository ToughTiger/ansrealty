<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Filament\Resources\PropertyResource\RelationManagers;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    
    protected static ?string $navigationGroup = 'Inventory';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Property Name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('builder_id')
                            ->label('Builder/Developer')
                            ->relationship('builder', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')->required(),
                                Forms\Components\TextInput::make('company_name'),
                                Forms\Components\TextInput::make('email')->email(),
                                Forms\Components\TextInput::make('phone')->tel(),
                            ])
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('project_name')
                            ->label('Project Name')
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('rera_number')
                            ->label('RERA Number')
                            ->maxLength(100)
                            ->columnSpan(1),
                        Forms\Components\Select::make('property_type')
                            ->options([
                                'Flat' => 'Apartment/Flat',
                                'Villa' => 'Villa',
                                'Plot' => 'Plot',
                                'Commercial' => 'Commercial',
                                'Penthouse' => 'Penthouse',
                                'Studio' => 'Studio Apartment',
                            ])
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Select::make('listing_type')
                            ->options([
                                'Sale' => 'For Sale',
                                'Rent' => 'For Rent',
                                'Lease' => 'For Lease',
                            ])
                            ->required()
                            ->default('Sale')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Location')
                    ->schema([
                        Forms\Components\TextInput::make('location')
                            ->label('Area/Locality')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('city')
                            ->required()
                            ->default('Mumbai')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('state')
                            ->required()
                            ->default('Maharashtra')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('pincode')
                            ->maxLength(10)
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Configuration')
                    ->schema([
                        Forms\Components\TextInput::make('bedrooms')
                            ->numeric()
                            ->minValue(0)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('bathrooms')
                            ->numeric()
                            ->minValue(0)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('balconies')
                            ->numeric()
                            ->minValue(0)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('parking')
                            ->label('Parking Spaces')
                            ->numeric()
                            ->minValue(0)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('carpet_area')
                            ->label('Carpet Area')
                            ->numeric()
                            ->suffix('sq.ft')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('built_up_area')
                            ->label('Built-up Area')
                            ->numeric()
                            ->suffix('sq.ft')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('floor_number')
                            ->label('Floor Number')
                            ->numeric()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('total_floors')
                            ->label('Total Floors')
                            ->numeric()
                            ->columnSpan(1),
                    ])
                    ->columns(4),
                    
                Forms\Components\Section::make('Pricing')
                    ->schema([
                        Forms\Components\TextInput::make('price_min')
                            ->label('Minimum Price')
                            ->numeric()
                            ->prefix('₹')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('price_max')
                            ->label('Maximum Price')
                            ->numeric()
                            ->prefix('₹')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Amenities & Features')
                    ->schema([
                        Forms\Components\CheckboxList::make('amenities')
                            ->options([
                                'Swimming Pool' => 'Swimming Pool',
                                'Gym' => 'Gym',
                                'Club House' => 'Club House',
                                'Children Play Area' => 'Children Play Area',
                                'Landscaped Gardens' => 'Landscaped Gardens',
                                'Power Backup' => 'Power Backup',
                                'Lift' => 'Lift',
                                '24x7 Security' => '24x7 Security',
                                'Gated Community' => 'Gated Community',
                                'Intercom' => 'Intercom',
                                'Piped Gas' => 'Piped Gas',
                                'Rain Water Harvesting' => 'Rain Water Harvesting',
                                'Visitor Parking' => 'Visitor Parking',
                                'CCTV Surveillance' => 'CCTV Surveillance',
                                'Fire Safety' => 'Fire Safety',
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                    
                Forms\Components\Section::make('Availability')
                    ->schema([
                        Forms\Components\Select::make('availability_status')
                            ->options([
                                'Available' => 'Available',
                                'Sold' => 'Sold',
                                'Reserved' => 'Reserved',
                                'On Hold' => 'On Hold',
                            ])
                            ->required()
                            ->default('Available')
                            ->columnSpan(1),
                        Forms\Components\Select::make('possession_status')
                            ->options([
                                'Ready to Move' => 'Ready to Move',
                                'Under Construction' => 'Under Construction',
                                'Upcoming' => 'Upcoming',
                            ])
                            ->required()
                            ->default('Under Construction')
                            ->columnSpan(1),
                        Forms\Components\DatePicker::make('possession_date')
                            ->label('Expected Possession')
                            ->native(false)
                            ->displayFormat('M Y')
                            ->columnSpan(1),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Property')
                            ->default(false)
                            ->columnSpan(1),
                        Forms\Components\Toggle::make('is_hot')
                            ->label('Hot Property')
                            ->helperText('Show in Hot Properties section')
                            ->default(false)
                            ->columnSpan(1),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->columnSpan(1),
                    ])
                    ->columns(3),
                    
                Forms\Components\Section::make('Media')
                    ->description('Upload property images, floor plans, and documents')
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->label('Property Images')
                            ->multiple()
                            ->image()
                            ->reorderable()
                            ->maxFiles(20)
                            ->directory('properties/images')
                            ->imageEditor()
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth(1200)
                            ->imageResizeTargetHeight(800)
                            ->helperText('Upload up to 20 high-quality images. Drag to reorder.')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('floor_plans')
                            ->label('Floor Plans')
                            ->multiple()
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                            ->maxFiles(10)
                            ->directory('properties/floor-plans')
                            ->helperText('Upload floor plans (PDF or images)')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('documents')
                            ->label('Documents')
                            ->multiple()
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxFiles(10)
                            ->directory('properties/documents')
                            ->helperText('Upload RERA certificates, brochures, etc. (PDF only)')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Image')
                    ->getStateUsing(fn ($record) => $record->images && is_array($record->images) && count($record->images) > 0 ? $record->images[0] : null)
                    ->square()
                    ->defaultImageUrl(asset('images/property-placeholder.jpg')),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->limit(30),
                Tables\Columns\TextColumn::make('builder.name')
                    ->label('Builder')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('property_type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state)))
                    ->searchable(),
                Tables\Columns\TextColumn::make('configuration')
                    ->label('Config')
                    ->getStateUsing(fn ($record) => $record->configuration)
                    ->limit(20),
                Tables\Columns\TextColumn::make('city')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('price_range')
                    ->label('Price')
                    ->getStateUsing(fn ($record) => $record->price_range),
                Tables\Columns\BadgeColumn::make('availability_status')
                    ->label('Status')
                    ->colors([
                        'success' => 'available',
                        'danger' => 'sold',
                        'warning' => 'under_negotiation',
                        'info' => 'reserved',
                    ])
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state))),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_hot')
                    ->label('Hot')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('builder_id')
                    ->label('Builder')
                    ->relationship('builder', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\SelectFilter::make('property_type')
                    ->options([
                        'residential_apartment' => 'Apartment',
                        'villa' => 'Villa',
                        'plot' => 'Plot',
                        'commercial' => 'Commercial',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('city')
                    ->options(fn () => Property::distinct()->pluck('city', 'city')->toArray())
                    ->searchable()
                    ->multiple(),
                Tables\Filters\SelectFilter::make('availability_status')
                    ->label('Status')
                    ->options([
                        'available' => 'Available',
                        'sold' => 'Sold',
                        'under_negotiation' => 'Under Negotiation',
                        'reserved' => 'Reserved',
                    ])
                    ->multiple(),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),
                Tables\Filters\TernaryFilter::make('is_hot')
                    ->label('Hot Property'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggleFeatured')
                    ->label(fn ($record) => $record->is_featured ? 'Unfeature' : 'Feature')
                    ->icon('heroicon-o-star')
                    ->color(fn ($record) => $record->is_featured ? 'warning' : 'gray')
                    ->action(fn ($record) => $record->update(['is_featured' => !$record->is_featured])),
                Tables\Actions\Action::make('toggleHot')
                    ->label(fn ($record) => $record->is_hot ? 'Remove Hot' : 'Mark Hot')
                    ->icon('heroicon-o-fire')
                    ->color(fn ($record) => $record->is_hot ? 'danger' : 'gray')
                    ->action(fn ($record) => $record->update(['is_hot' => !$record->is_hot])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('markAvailable')
                        ->label('Mark Available')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['availability_status' => 'available'])),
                    Tables\Actions\BulkAction::make('markSold')
                        ->label('Mark Sold')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn ($records) => $records->each->update(['availability_status' => 'sold'])),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'view' => Pages\ViewProperty::route('/{record}'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        try {
            return static::getModel()::where('availability_status', 'available')->where('is_active', true)->count();
        } catch (\Exception $e) {
            return null;
        }
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}