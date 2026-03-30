<?php

namespace App\Filament\Resources\AssignmentRuleResource\Pages;

use App\Filament\Resources\AssignmentRuleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssignmentRule extends CreateRecord
{
    protected static string $resource = AssignmentRuleResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
