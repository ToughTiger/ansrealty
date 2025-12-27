<?php

namespace App\Filament\Resources\BuilderResource\Pages;

use App\Filament\Resources\BuilderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBuilder extends ViewRecord
{
    protected static string $resource = BuilderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
