@echo off
echo Installing required packages...
echo.

echo [1/3] Installing Spatie Laravel Activity Log...
composer require spatie/laravel-activitylog

echo.
echo [2/3] Installing Laravel Excel...
composer require maatwebsite/laravel-excel

echo.
echo [3/3] Installing Filament Media Library Plugin...
composer require filament/spatie-laravel-media-library-plugin

echo.
echo Publishing package configurations...
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config

echo.
echo Installation complete!
echo Please run: php artisan migrate
pause
