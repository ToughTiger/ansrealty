<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebhookResource\Pages;
use App\Models\Webhook;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class WebhookResource extends Resource
{
    protected static ?string $model = Webhook::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';
    
    protected static ?string $navigationGroup = 'Settings';
    
    protected static ?int $navigationSort = 99;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Webhook Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Facebook Lead Ads')
                            ->columnSpan(1),
                        
                        Forms\Components\Select::make('type')
                            ->options([
                                'meta' => '📘 Meta (Facebook)',
                                'google' => '🔍 Google Ads',
                                'api' => '🔗 Generic API',
                                'custom' => '⚙️ Custom',
                            ])
                            ->required()
                            ->default('custom')
                            ->reactive()
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('endpoint')
                            ->label('Endpoint URL')
                            ->required()
                            ->url()
                            ->prefix('https://')
                            ->placeholder('yourdomain.com/api/webhooks/meta-leads')
                            ->helperText('Full webhook URL')
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('verify_token')
                            ->label('Verify Token')
                            ->placeholder('ansrealty_webhook_token')
                            ->helperText('Required for Meta webhooks')
                            ->visible(fn ($get) => $get('type') === 'meta')
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => '✅ Active',
                                'inactive' => '❌ Inactive',
                                'testing' => '🧪 Testing',
                            ])
                            ->required()
                            ->default('active')
                            ->columnSpan(1),
                        
                        Forms\Components\Textarea::make('description')
                            ->placeholder('Brief description of this webhook...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
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
                        'primary' => 'meta',
                        'success' => 'google',
                        'info' => 'api',
                        'gray' => 'custom',
                    ])
                    ->icons([
                        'heroicon-o-globe-alt' => 'meta',
                        'heroicon-o-magnifying-glass' => 'google',
                        'heroicon-o-link' => 'api',
                        'heroicon-o-cog' => 'custom',
                    ])
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('endpoint')
                    ->limit(50)
                    ->copyable()
                    ->copyMessage('Copied!')
                    ->tooltip('Click to copy'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                        'warning' => 'testing',
                    ]),
                
                Tables\Columns\TextColumn::make('total_calls')
                    ->label('Total')
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('successful_calls')
                    ->label('Success')
                    ->color('success')
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('failed_calls')
                    ->label('Failed')
                    ->color('danger')
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('success_rate')
                    ->label('Success %')
                    ->suffix('%')
                    ->color(fn ($state) => $state >= 90 ? 'success' : ($state >= 70 ? 'warning' : 'danger'))
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('last_called_at')
                    ->label('Last Called')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'meta' => 'Meta',
                        'google' => 'Google',
                        'api' => 'API',
                        'custom' => 'Custom',
                    ])
                    ->multiple(),
                
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'testing' => 'Testing',
                    ])
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\Action::make('test')
                    ->label('Test')
                    ->icon('heroicon-o-beaker')
                    ->color('warning')
                    ->form([
                        Forms\Components\Textarea::make('test_payload')
                            ->label('Test JSON Payload')
                            ->rows(10)
                            ->placeholder('{"full_name": "Test User", "mobile": "9876543210"}')
                            ->helperText('Enter JSON data to test the webhook'),
                    ])
                    ->action(function (Webhook $record, array $data) {
                        try {
                            $payload = json_decode($data['test_payload'], true);
                            
                            $response = Http::post($record->endpoint, $payload);
                            
                            if ($response->successful()) {
                                Notification::make()
                                    ->title('Webhook test successful!')
                                    ->body('Status: ' . $response->status() . ' | Response: ' . $response->body())
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Webhook test failed!')
                                    ->body('Status: ' . $response->status())
                                    ->danger()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Test failed!')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                
                Tables\Actions\Action::make('view_setup')
                    ->label('Setup Guide')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->modalHeading(fn (Webhook $record) => $record->name . ' - Setup Guide')
                    ->modalContent(view('filament.pages.webhook-quick-guide'))
                    ->modalWidth('5xl')
                    ->slideOver(),
                
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListWebhooks::route('/'),
            'create' => Pages\CreateWebhook::route('/create'),
            'edit' => Pages\EditWebhook::route('/{record}/edit'),
        ];
    }
}
