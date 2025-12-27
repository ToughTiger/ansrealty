<?php

namespace App\Filament\Resources\LandingPageResource\Pages;

use App\Filament\Resources\LandingPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLandingPage extends EditRecord
{
    protected static string $resource = LandingPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('visit')
                ->label('Visit Landing Page')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn () => $this->record->url)
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }
}
