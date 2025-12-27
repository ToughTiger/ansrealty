@echo off
echo Clearing Laravel and Filament cache...
echo.

php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan filament:cache-components

echo.
echo Cache cleared successfully!
echo.
echo Now try accessing: http://localhost:8000/admin
echo.
pause
