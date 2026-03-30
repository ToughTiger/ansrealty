<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommunicationTemplateResource\Pages;
use App\Models\CommunicationTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CommunicationTemplateResource extends Resource
{
    protected static ?string $model = CommunicationTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Communication';

    protected static ?string $navigationLabel = 'Templates';

    protected static ?int $navigationSort = 60;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Template Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                'email' => 'Email',
                                'whatsapp' => 'WhatsApp',
                                'sms' => 'SMS',
                            ])
                            ->reactive(),

                        Forms\Components\Select::make('category')
                            ->options([
                                'lead_followup' => 'Lead Follow-up',
                                'booking_confirmation' => 'Booking Confirmation',
                                'payment_reminder' => 'Payment Reminder',
                                'site_visit_reminder' => 'Site Visit Reminder',
                                'welcome' => 'Welcome Message',
                                'thank_you' => 'Thank You',
                                'general' => 'General',
                            ]),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->label('Subject (for Email)')
                            ->visible(fn (Forms\Get $get) => $get('type') === 'email')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('body')
                            ->required()
                            ->rows(10)
                            ->columnSpanFull()
                            ->helperText('Use {variable_name} for dynamic content. E.g., {customer_name}, {property_name}, {amount}'),
                    ]),

                Forms\Components\Section::make('Available Variables')
                    ->schema([
                        Forms\Components\TagsInput::make('variables')
                            ->label('Variable Names')
                            ->helperText('List all variables used in the template body (without curly braces)')
                            ->placeholder('customer_name, property_name, amount, etc.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'email',
                        'success' => 'whatsapp',
                        'warning' => 'sms',
                    ])
                    ->icons([
                        'heroicon-o-envelope' => 'email',
                        'heroicon-o-chat-bubble-left-right' => 'whatsapp',
                        'heroicon-o-device-phone-mobile' => 'sms',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject')
                    ->limit(30)
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('body')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('communications_count')
                    ->label('Times Used')
                    ->counts('communications')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'email' => 'Email',
                        'whatsapp' => 'WhatsApp',
                        'sms' => 'SMS',
                    ]),

                Tables\Filters\SelectFilter::make('category'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->modalContent(fn (CommunicationTemplate $record) => view('filament.modals.template-preview', ['template' => $record]))
                    ->modalSubmitAction(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommunicationTemplates::route('/'),
            'create' => Pages\CreateCommunicationTemplate::route('/create'),
            'edit' => Pages\EditCommunicationTemplate::route('/{record}/edit'),
        ];
    }
}
