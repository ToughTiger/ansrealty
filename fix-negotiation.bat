@echo off
echo Creating NegotiationResource pages directory...

mkdir "app\Filament\Resources\NegotiationResource" 2>nul
mkdir "app\Filament\Resources\NegotiationResource\Pages" 2>nul

echo Directory created!
echo.
echo Now creating page files...
echo.

REM Create the page content
(
echo ^<?php
echo.
echo namespace App\Filament\Resources\NegotiationResource\Pages;
echo.
echo use App\Filament\Resources\NegotiationResource;
echo use Filament\Actions;
echo use Filament\Resources\Pages\ManageRecords;
echo.
echo class ManageNegotiations extends ManageRecords
echo {
echo     protected static string $resource = NegotiationResource::class;
echo.
echo     protected function getHeaderActions(^): array
echo     {
echo         return [
echo             Actions\CreateAction::make(^),
echo         ];
echo     }
echo }
) > "app\Filament\Resources\NegotiationResource\Pages\ManageNegotiations.php"

echo.
echo Page file created!
echo.
echo Clearing cache...
php artisan optimize:clear

echo.
echo ========================================
echo Complete! Refresh browser now.
echo ========================================
pause
