<?php

namespace App\Filament\Resources\SiteVisitResource\Pages;

use App\Filament\Resources\SiteVisitResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSiteVisit extends ViewRecord
{
    protected static string $resource = SiteVisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
