<?php

namespace App\Filament\Resources\AssignmentRuleResource\Pages;

use App\Filament\Resources\AssignmentRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssignmentRule extends EditRecord
{
    protected static string $resource = AssignmentRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
