<?php

namespace App\Filament\Resources\LeadSourceResource\Pages;

use App\Filament\Resources\LeadSourceResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListLeadSources extends ListRecords
{
    protected static string $resource = LeadSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
