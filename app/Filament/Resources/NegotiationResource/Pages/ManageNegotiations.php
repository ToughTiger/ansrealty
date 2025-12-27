<?php

namespace App\Filament\Resources\NegotiationResource\Pages;

use App\Filament\Resources\NegotiationResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNegotiations extends ManageRecords
{
    protected static string $resource = NegotiationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
