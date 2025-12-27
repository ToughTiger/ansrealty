@echo off
echo ============================================
echo QUICK FIX - Installing Media Library NOW
echo ============================================
echo.
echo This will take 2-3 minutes. Please wait...
echo.

echo [1/6] Installing Spatie Media Library (latest stable)...
call composer require "spatie/laravel-medialibrary" --no-interaction

echo.
echo [2/6] Installing Filament Plugin...
call composer require "filament/spatie-laravel-media-library-plugin:^3.2" --no-interaction

echo.
echo [3/6] Publishing migrations...
call php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations" --force

echo.
echo [4/6] Running migrations...
call php artisan migrate --force

echo.
echo [5/6] Creating storage link...
call php artisan storage:link

echo.
echo [6/6] Clearing cache...
call php artisan config:clear
call php artisan cache:clear
call php artisan view:clear
call php artisan route:clear
call php artisan optimize:clear

echo.
echo ============================================
echo DONE! Installation Complete
echo ============================================
echo.
echo Now refresh your browser: http://ansrealty.test/admin
echo.
pause
