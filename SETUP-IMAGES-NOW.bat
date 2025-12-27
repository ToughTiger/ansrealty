@echo off
echo ============================================
echo SETUP IMAGE UPLOAD - Simple Solution
echo ============================================
echo.

echo [1/3] Running migrations...
call php artisan migrate --force

echo.
echo [2/3] Creating storage link...
call php artisan storage:link

echo.
echo [3/3] Clearing cache...
call php artisan config:clear
call php artisan cache:clear
call php artisan view:clear
call php artisan route:clear
call php artisan optimize:clear

echo.
echo ============================================
echo DONE! Setup Complete
echo ============================================
echo.
echo Image upload is now ready!
echo.
echo Go to: http://ansrealty.test/admin
echo - Properties ^> Create/Edit ^> Media section
echo - Landing Pages ^> Create/Edit ^> Gallery section
echo.
pause
