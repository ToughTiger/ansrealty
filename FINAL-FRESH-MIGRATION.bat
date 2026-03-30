@echo off
echo ========================================
echo  FINAL FIX - Fresh Migration
echo ========================================
echo.

cd /d "%~dp0"

echo Step 1: Clearing any failed migrations...
php artisan migrate:reset --force

echo.
echo Step 2: Running fresh migrations...
php artisan migrate:fresh --force

echo.
echo Step 3: Running seeders...
php artisan db:seed --force

echo.
echo Step 4: Clearing caches...
php artisan optimize:clear
php artisan filament:cache-components

echo.
echo ========================================
echo  ✅ DONE!
echo ========================================
echo.
echo Login credentials:
echo Email: admin@ansrealty.com
echo Password: password
echo.
pause
