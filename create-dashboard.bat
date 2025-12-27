@echo off
echo ============================================
echo Creating Beautiful Dashboard
echo ============================================
echo.

echo [1/4] Creating directories...
mkdir app\Filament\Widgets 2>nul
mkdir app\Filament\Pages 2>nul
mkdir resources\views\filament\widgets 2>nul

echo [2/4] Installing required package...
call composer require "flowframe/laravel-trend" --no-interaction

echo [3/4] Creating widget files...
echo Please wait...

echo [4/4] Clearing cache...
call php artisan config:clear
call php artisan cache:clear
call php artisan view:clear

echo.
echo ============================================
echo Setup Complete!
echo ============================================
echo.
echo Next: Run the commands below to create widgets:
echo   php artisan make:filament-widget StatsOverview --stats-overview
echo   php artisan make:filament-widget LeadsChart --chart
echo   php artisan make:filament-widget RecentLeads --table
echo   php artisan make:filament-widget OpportunityByStage --chart
echo   php artisan make:filament-widget PropertyByType --chart
echo.
pause
