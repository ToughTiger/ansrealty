<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LandingPageResource\Pages;
use App\Models\LandingPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Str;

class LandingPageResource extends Resource
{
    protected static ?string $model = LandingPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';
    
    protected static ?string $navigationGroup = 'Marketing';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Select::make('property_id')
                            ->label('Linked Property')
                            ->relationship('property', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('This will be used in the URL: /landing/your-slug')
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('subtitle')
                            ->maxLength(500)
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('campaign_source')
                            ->label('Campaign Source')
                            ->helperText('e.g., Google Ads, Facebook Ads, Instagram')
                            ->maxLength(100)
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('SEO Meta Tags')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('meta_description')
                            ->maxLength(500)
                            ->rows(2)
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('meta_keywords')
                            ->helperText('Comma separated keywords')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),
                    
                Forms\Components\Section::make('Hero Section')
                    ->schema([
                        Forms\Components\TextInput::make('hero_heading')
                            ->label('Heading')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('hero_subheading')
                            ->label('Subheading')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('hero_image')
                            ->label('Hero Background Image')
                            ->image()
                            ->directory('landing-pages/hero')
                            ->imageEditor()
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth(1920)
                            ->imageResizeTargetHeight(1080)
                            ->helperText('Upload a high-quality hero image (1920x1080 recommended)')
                            ->columnSpanFull(),
                    ]),
                    
                Forms\Components\Section::make('Call to Action')
                    ->schema([
                        Forms\Components\Textarea::make('cta_text')
                            ->label('CTA Text')
                            ->rows(3)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('cta_button_text')
                            ->label('Button Text')
                            ->default('Book Now')
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Property Features')
                    ->schema([
                        Forms\Components\Repeater::make('features')
                            ->schema([
                                Forms\Components\TextInput::make('title')->required(),
                                Forms\Components\TextInput::make('value')->required(),
                                Forms\Components\TextInput::make('icon')
                                    ->helperText('Font Awesome icon class (e.g., fa-bed)'),
                            ])
                            ->columns(3)
                            ->defaultItems(4)
                            ->columnSpanFull(),
                    ]),
                    
                Forms\Components\Section::make('Amenities')
                    ->schema([
                        Forms\Components\Repeater::make('amenities')
                            ->schema([
                                Forms\Components\TextInput::make('name')->required(),
                                Forms\Components\TextInput::make('icon')
                                    ->helperText('Font Awesome icon class'),
                            ])
                            ->columns(2)
                            ->defaultItems(5)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Location Benefits')
                    ->schema([
                        Forms\Components\Repeater::make('location_benefits')
                            ->schema([
                                Forms\Components\TextInput::make('place')->required(),
                                Forms\Components\TextInput::make('distance')->required(),
                                Forms\Components\TextInput::make('icon')
                                    ->helperText('Font Awesome icon class'),
                            ])
                            ->columns(3)
                            ->defaultItems(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Special Offer')
                    ->schema([
                        Forms\Components\RichEditor::make('special_offer_text')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Lead Form')
                    ->schema([
                        Forms\Components\TextInput::make('form_heading')
                            ->default('Get Free Consultation')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('form_subheading')
                            ->rows(2)
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
                    
                Forms\Components\Section::make('Gallery & Images')
                    ->description('Upload images for gallery and featured property section')
                    ->schema([
                        Forms\Components\FileUpload::make('gallery_images')
                            ->label('Property Gallery')
                            ->multiple()
                            ->image()
                            ->reorderable()
                            ->maxFiles(10)
                            ->directory('landing-pages/gallery')
                            ->imageEditor()
                            ->imageCropAspectRatio('4:3')
                            ->imageResizeTargetWidth(800)
                            ->imageResizeTargetHeight(600)
                            ->helperText('Upload up to 10 gallery images. Drag to reorder.')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Featured Property Image')
                            ->image()
                            ->directory('landing-pages/featured')
                            ->imageEditor()
                            ->imageCropAspectRatio('3:2')
                            ->imageResizeTargetWidth(800)
                            ->imageResizeTargetHeight(533)
                            ->helperText('This image will be shown in the consultation section (right side)')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(false),
                    
                Forms\Components\Section::make('Status & Tracking')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('views_count')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('leads_count')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->columnSpan(1),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('hero_image')
                    ->label('Hero')
                    ->square()
                    ->defaultImageUrl(asset('images/landing-placeholder.jpg')),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->limit(30),
                Tables\Columns\TextColumn::make('property.name')
                    ->label('Property')
                    ->searchable()
                    ->sortable()
                    ->limit(25),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Slug copied')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('campaign_source')
                    ->label('Source')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('views_count')
                    ->label('Views')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('leads_count')
                    ->label('Leads')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('conversion_rate')
                    ->label('CVR')
                    ->getStateUsing(fn ($record) => $record->conversion_rate . '%')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('property_id')
                    ->label('Property')
                    ->relationship('property', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\SelectFilter::make('campaign_source')
                    ->options(fn () => LandingPage::distinct()->pluck('campaign_source', 'campaign_source')->toArray())
                    ->multiple(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('visit')
                    ->label('Visit')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn ($record) => $record->url)
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->label('Duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function ($record) {
                        $newRecord = $record->replicate();
                        $newRecord->slug = $record->slug . '-copy-' . time();
                        $newRecord->title = $record->title . ' (Copy)';
                        $newRecord->views_count = 0;
                        $newRecord->leads_count = 0;
                        $newRecord->save();
                    })
                    ->requiresConfirmation(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => false])),
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
            'index' => Pages\ListLandingPages::route('/'),
            'create' => Pages\CreateLandingPage::route('/create'),
            'edit' => Pages\EditLandingPage::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        try {
            return static::getModel()::where('is_active', true)->count();
        } catch (\Exception $e) {
            return null;
        }
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
