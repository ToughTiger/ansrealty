<?php

namespace App\Filament\Resources\CommunicationTemplateResource\Pages;

use App\Filament\Resources\CommunicationTemplateResource;
use Filament\Resources\Pages\ListRecords;

class ListCommunicationTemplates extends ListRecords
{
    protected static string $resource = CommunicationTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
