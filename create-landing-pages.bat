@echo off
echo Creating Landing Page Filament Pages...
echo.

REM Create directory
mkdir app\Filament\Resources\LandingPageResource\Pages 2>nul

REM Create ListLandingPages.php
echo Creating ListLandingPages.php...
(
echo ^<?php
echo.
echo namespace App\Filament\Resources\LandingPageResource\Pages;
echo.
echo use App\Filament\Resources\LandingPageResource;
echo use Filament\Actions;
echo use Filament\Resources\Pages\ListRecords;
echo.
echo class ListLandingPages extends ListRecords
echo {
echo     protected static string $resource = LandingPageResource::class;
echo.
echo     protected function getHeaderActions(^): array
echo     {
echo         return [
echo             Actions\CreateAction::make(^),
echo         ];
echo     }
echo }
) > app\Filament\Resources\LandingPageResource\Pages\ListLandingPages.php

REM Create CreateLandingPage.php
echo Creating CreateLandingPage.php...
(
echo ^<?php
echo.
echo namespace App\Filament\Resources\LandingPageResource\Pages;
echo.
echo use App\Filament\Resources\LandingPageResource;
echo use Filament\Actions;
echo use Filament\Resources\Pages\CreateRecord;
echo.
echo class CreateLandingPage extends CreateRecord
echo {
echo     protected static string $resource = LandingPageResource::class;
echo }
) > app\Filament\Resources\LandingPageResource\Pages\CreateLandingPage.php

REM Create EditLandingPage.php
echo Creating EditLandingPage.php...
(
echo ^<?php
echo.
echo namespace App\Filament\Resources\LandingPageResource\Pages;
echo.
echo use App\Filament\Resources\LandingPageResource;
echo use Filament\Actions;
echo use Filament\Resources\Pages\EditRecord;
echo.
echo class EditLandingPage extends EditRecord
echo {
echo     protected static string $resource = LandingPageResource::class;
echo.
echo     protected function getHeaderActions(^): array
echo     {
echo         return [
echo             Actions\Action::make('visit'^)
echo                 -^>label('Visit Landing Page'^)
echo                 -^>icon('heroicon-o-arrow-top-right-on-square'^)
echo                 -^>url(fn (^) =^> $this-^>record-^>url^)
echo                 -^>openUrlInNewTab(^),
echo             Actions\DeleteAction::make(^),
echo         ];
echo     }
echo }
) > app\Filament\Resources\LandingPageResource\Pages\EditLandingPage.php

echo.
echo ============================================
echo Files created successfully!
echo ============================================
echo.
echo Now run these commands:
echo   php artisan migrate
echo   php artisan cache:clear
echo   php artisan config:clear
echo.
pause
