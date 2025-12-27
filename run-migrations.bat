@echo off
echo ========================================
echo Running Database Migrations
echo ========================================
echo.

echo [1/3] Dropping all tables and running fresh migrations...
php artisan migrate:fresh

echo.
echo [2/3] Running seeders...
php artisan db:seed

echo.
echo [3/3] Clearing cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo.
echo ========================================
echo Migration Complete!
echo ========================================
echo.
pause
