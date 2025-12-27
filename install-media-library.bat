@echo off
echo ============================================
echo Installing Spatie Media Library
echo ============================================
echo.

echo Step 1: Installing Media Library package (Laravel 12 compatible)...
composer require "spatie/laravel-medialibrary:^12.0"

if %errorlevel% neq 0 (
    echo Error: Failed to install media library package
    pause
    exit /b 1
)

echo.
echo Step 2: Installing Filament Media Library Plugin...
composer require "filament/spatie-laravel-media-library-plugin:^3.2"

if %errorlevel% neq 0 (
    echo Error: Failed to install Filament plugin
    pause
    exit /b 1
)

echo.
echo Step 3: Publishing configuration and migrations...
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-config"

echo.
echo Step 4: Running migrations...
php artisan migrate

if %errorlevel% neq 0 (
    echo Warning: Migration may have failed. Check the output above.
)

echo.
echo Step 5: Creating storage link...
php artisan storage:link

echo.
echo Step 6: Clearing cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear

echo.
echo ============================================
echo Installation Complete!
echo ============================================
echo.
echo Media Library has been installed successfully.
echo You can now upload images in the admin panel.
echo.
echo Next: Refresh your admin panel page
echo.
pause
