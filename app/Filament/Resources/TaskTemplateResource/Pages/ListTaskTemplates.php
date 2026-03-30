<?php

namespace App\Filament\Resources\TaskTemplateResource\Pages;

use App\Filament\Resources\TaskTemplateResource;
use Filament\Resources\Pages\ListRecords;

class ListTaskTemplates extends ListRecords
{
    protected static string $resource = TaskTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
