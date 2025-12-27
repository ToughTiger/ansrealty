<?php

namespace App\Filament\Resources\BuilderResource\Pages;

use App\Filament\Resources\BuilderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBuilders extends ListRecords
{
    protected static string $resource = BuilderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
