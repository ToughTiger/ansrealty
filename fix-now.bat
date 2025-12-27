@echo off
echo ========================================
echo Quick Fix - Remove Broken Resource
echo ========================================
echo.

echo Renaming NegotiationResource.php (temporary)...
ren "app\Filament\Resources\NegotiationResource.php" "NegotiationResource.php.bak" 2>nul

echo.
echo Clearing cache...
php artisan optimize:clear

echo.
echo ========================================
echo Fix Applied!
echo ========================================
echo.
echo Refresh browser now (Ctrl + F5)
echo You should see all other resources working
echo.
echo NegotiationResource will be added properly later
echo.
pause
