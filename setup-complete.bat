@echo off
echo ========================================
echo ANS Realty CRM - Complete Setup Script
echo ========================================
echo.

echo [Step 1/6] Installing Composer Packages...
echo.
call composer require spatie/laravel-activitylog
if %errorlevel% neq 0 (
    echo ERROR: Failed to install spatie/laravel-activitylog
    pause
    exit /b 1
)

call composer require maatwebsite/laravel-excel
if %errorlevel% neq 0 (
    echo ERROR: Failed to install maatwebsite/laravel-excel
    pause
    exit /b 1
)

call composer require spatie/laravel-medialibrary
if %errorlevel% neq 0 (
    echo ERROR: Failed to install spatie/laravel-medialibrary
    pause
    exit /b 1
)

echo.
echo [Step 2/6] Publishing Package Configurations...
echo.
call php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
call php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
call php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
call php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"

echo.
echo [Step 3/6] Running Database Migrations...
echo.
call php artisan migrate
if %errorlevel% neq 0 (
    echo ERROR: Migration failed!
    pause
    exit /b 1
)

echo.
echo [Step 4/6] Seeding Database with Initial Data...
echo.
call php artisan db:seed
if %errorlevel% neq 0 (
    echo ERROR: Seeding failed!
    pause
    exit /b 1
)

echo.
echo [Step 5/6] Generating Shield Resources...
echo.
call php artisan shield:generate --all
if %errorlevel% neq 0 (
    echo WARNING: Shield generation encountered issues
)

echo.
echo [Step 6/6] Clearing Cache...
echo.
call php artisan optimize:clear
call php artisan filament:cache-components

echo.
echo ========================================
echo SUCCESS! Setup Complete
echo ========================================
echo.
echo Next Steps:
echo 1. Create admin: php artisan shield:super-admin
echo 2. Start server: php artisan serve
echo 3. Visit: http://localhost:8000/admin
echo.
echo Press any key to exit...
pause > nul
