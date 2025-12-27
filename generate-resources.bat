@echo off
echo Creating Filament Resource structure...
echo.

REM Create LeadResource directories
mkdir "app\Filament\Resources\LeadResource\Pages" 2>nul
mkdir "app\Filament\Resources\LeadResource\RelationManagers" 2>nul
mkdir "app\Filament\Resources\LeadResource\Widgets" 2>nul

REM Create OpportunityResource directories
mkdir "app\Filament\Resources\OpportunityResource\Pages" 2>nul
mkdir "app\Filament\Resources\OpportunityResource\RelationManagers" 2>nul

REM Create BuilderResource directories
mkdir "app\Filament\Resources\BuilderResource\Pages" 2>nul

REM Create SiteVisitResource directories
mkdir "app\Filament\Resources\SiteVisitResource\Pages" 2>nul

REM Create TaskResource directories
mkdir "app\Filament\Resources\TaskResource\Pages" 2>nul

REM Create CommissionResource directories
mkdir "app\Filament\Resources\CommissionResource\Pages" 2>nul

echo Directories created!
echo.
echo Now generating resources with Artisan...
echo.

php artisan make:filament-resource Lead --generate
php artisan make:filament-resource Opportunity --generate
php artisan make:filament-resource Builder --generate
php artisan make:filament-resource SiteVisit --generate
php artisan make:filament-resource Task --generate
php artisan make:filament-resource Commission --generate

echo.
echo Resource generation complete!
pause
