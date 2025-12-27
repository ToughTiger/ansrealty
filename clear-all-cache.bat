@echo off
echo ===================================
echo Quick Fix - Clear All Caches
echo ===================================
echo.

echo [1/5] Clearing application cache...
php artisan cache:clear

echo [2/5] Clearing config cache...
php artisan config:clear

echo [3/5] Clearing route cache...
php artisan route:clear

echo [4/5] Clearing view cache...
php artisan view:clear

echo [5/5] Clearing compiled files...
php artisan optimize:clear

echo.
echo [BONUS] Clearing Filament cache...
php artisan filament:cache-components

echo.
echo ===================================
echo All caches cleared!
echo ===================================
echo.
echo Now try: php artisan serve
echo Then visit: http://localhost:8000/admin
echo.
pause
