@echo off
echo ========================================
echo  SIMPLE FRESH MIGRATION
echo  (Drops everything, runs seeders)
echo ========================================
echo.
echo WARNING: This will:
echo  - Drop ALL tables (including users)
echo  - Run all migrations fresh
echo  - Run all seeders
echo.
echo Press Ctrl+C to cancel, or
pause

cd /d "%~dp0"

echo.
echo Step 1: Running fresh migrations...
php artisan migrate:fresh --force

echo.
echo Step 2: Running seeders...
php artisan db:seed --force

echo.
echo Step 3: Clearing caches...
php artisan optimize:clear
php artisan filament:cache-components

echo.
echo ========================================
echo  ✅ Fresh Migration Complete!
echo ========================================
echo.
echo Default admin user:
echo Email: admin@ansrealty.com
echo Password: password
echo.
pause
