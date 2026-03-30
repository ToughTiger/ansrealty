<?php

namespace App\Filament\Resources\OpportunityStageResource\Pages;

use App\Filament\Resources\OpportunityStageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOpportunityStage extends EditRecord
{
    protected static string $resource = OpportunityStageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
