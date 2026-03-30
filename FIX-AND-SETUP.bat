@echo off
echo ========================================
echo   ANS Realty - Quick Fix
echo ========================================
echo.

echo Step 1: Publishing activity log migration...
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"

echo.
echo Step 2: Running all migrations...
php artisan migrate

echo.
echo Step 3: Running seeders...
php artisan db:seed --class=LeadSourceSeeder
php artisan db:seed --class=LeadStatusSeeder
php artisan db:seed --class=OpportunityStageSeeder
php artisan db:seed --class=ComprehensiveSeeder

echo.
echo Step 4: Clearing caches...
php artisan optimize:clear

echo.
echo ========================================
echo   Fixed! Now creating Filament resources...
echo ========================================
echo.
php artisan make:filament-resource Agent --generate
php artisan make:filament-resource Booking --generate
php artisan make:filament-resource User --generate

echo.
echo Done! Visit /admin
echo.
pause
