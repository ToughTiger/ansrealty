<?php

namespace App\Filament\Resources\OpportunityStageResource\Pages;

use App\Filament\Resources\OpportunityStageResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListOpportunityStages extends ListRecords
{
    protected static string $resource = OpportunityStageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
