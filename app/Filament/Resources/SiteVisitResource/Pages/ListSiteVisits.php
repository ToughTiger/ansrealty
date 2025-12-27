<?php

namespace App\Filament\Resources\SiteVisitResource\Pages;

use App\Filament\Resources\SiteVisitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSiteVisits extends ListRecords
{
    protected static string $resource = SiteVisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
