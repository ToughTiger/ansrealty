<?php

namespace App\Filament\Resources\AssignmentRuleResource\Pages;

use App\Filament\Resources\AssignmentRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssignmentRules extends ListRecords
{
    protected static string $resource = AssignmentRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Assignment Rule')
                ->icon('heroicon-o-plus'),
        ];
    }
}
