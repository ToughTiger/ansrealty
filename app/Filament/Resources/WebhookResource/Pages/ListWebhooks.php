<?php

namespace App\Filament\Resources\WebhookResource\Pages;

use App\Filament\Resources\WebhookResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebhooks extends ListRecords
{
    protected static string $resource = WebhookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Webhook')
                ->icon('heroicon-o-plus'),
            
            Actions\Action::make('quick_setup')
                ->label('Quick Setup Guide')
                ->icon('heroicon-o-document-text')
                ->color('info')
                ->modalHeading('Webhook Quick Setup')
                ->modalContent(view('filament.pages.webhook-quick-guide'))
                ->modalWidth('5xl')
                ->slideOver(),
        ];
    }
}
