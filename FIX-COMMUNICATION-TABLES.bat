@echo off
echo Fixing communication tables...
echo.

cd /d "%~dp0"

echo Step 1: Dropping existing tables (if any)...
php artisan tinker --execute="use Illuminate\Support\Facades\DB; DB::statement('DROP TABLE IF EXISTS communications'); DB::statement('DROP TABLE IF EXISTS communication_templates'); echo 'Tables dropped';"

echo.
echo Step 2: Running migrations...
php artisan migrate --force

echo.
echo Step 3: Clearing caches...
php artisan optimize:clear
php artisan filament:cache-components

echo.
echo Done! Communication tables created successfully.
echo.
pause
