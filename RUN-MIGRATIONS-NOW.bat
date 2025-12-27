@echo off
echo ============================================
echo RUNNING ALL MIGRATIONS NOW
echo ============================================
echo.
echo This will create all required database tables...
echo.

echo Step 1: Checking database connection...
php artisan db:show

echo.
echo Step 2: Running migrations...
php artisan migrate --force

if %errorlevel% neq 0 (
    echo.
    echo ============================================
    echo ERROR: Migration failed!
    echo ============================================
    echo.
    echo Please check:
    echo 1. Database exists in MySQL
    echo 2. Database credentials in .env file are correct
    echo 3. MySQL service is running
    echo.
    pause
    exit /b 1
)

echo.
echo Step 3: Creating storage link...
php artisan storage:link

echo.
echo Step 4: Clearing all caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear

echo.
echo ============================================
echo SUCCESS! All migrations completed
echo ============================================
echo.
echo Database tables created:
echo - properties (with image columns)
echo - landing_pages
echo - leads
echo - opportunities
echo - site_visits
echo - tasks
echo - negotiations
echo - commissions
echo - post_sales
echo - and more...
echo.
echo Now go to: http://ansrealty.test/admin
echo.
pause
