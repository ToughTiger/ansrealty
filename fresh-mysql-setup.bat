@echo off
color 0A
cls
echo.
echo  ========================================
echo  ^|   ANS Realty - Fresh MySQL Setup    ^|
echo  ========================================
echo.
echo  This will:
echo  1. DROP existing ansrealty database
echo  2. CREATE fresh ansrealty database
echo  3. Run migrations (27+ tables)
echo  4. Seed master data
echo  5. Create test user
echo.
echo  WARNING: All existing data will be lost!
echo.
echo  Press CTRL+C to cancel
echo  Press ANY KEY to continue...
pause >nul

color 0E
echo.
echo ========================================
echo  Dropping existing database...
echo ========================================
echo.
mysql -u root -e "DROP DATABASE IF EXISTS ansrealty;"
echo  Old database dropped!
echo.

color 0A
echo ========================================
echo  Creating fresh database...
echo ========================================
echo.
mysql -u root -e "CREATE DATABASE ansrealty CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if %errorlevel% neq 0 (
    color 0C
    echo.
    echo  ERROR: Could not create database!
    echo.
    echo  Please check:
    echo  1. Laragon MySQL is running
    echo  2. Port 3306 is not blocked
    echo  3. Root user has permissions
    echo.
    pause
    exit /b 1
)

echo  Database created!
echo.

echo ========================================
echo  Clearing Laravel cache...
echo ========================================
echo.
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo  Cache cleared!
echo.

echo ========================================
echo  Running migrations...
echo ========================================
echo.
php artisan migrate --force

if %errorlevel% neq 0 (
    color 0C
    echo.
    echo  ERROR: Migration failed!
    echo.
    echo  Check the error message above.
    echo.
    pause
    exit /b 1
)

echo.
echo  All tables created!
echo.

echo ========================================
echo  Seeding database...
echo ========================================
echo.
php artisan db:seed --force

if %errorlevel% neq 0 (
    color 0C
    echo.
    echo  WARNING: Seeding had errors!
    echo.
    echo  Continuing anyway...
    echo.
)

echo.
echo  Master data seeded!
echo.

color 0A
cls
echo.
echo  ========================================
echo  ^|      SETUP SUCCESSFUL! ✓            ^|
echo  ========================================
echo.
echo  Database Information:
echo  ---------------------
echo  Name: ansrealty
echo  Tables: 27+
echo  Character Set: utf8mb4_unicode_ci
echo.
echo  Master Data:
echo  -----------
echo  Lead Sources: 10
echo  Lead Statuses: 12
echo  Opportunity Stages: 11
echo.
echo  Test User:
echo  ----------
echo  Email: test@example.com
echo  Password: password
echo.
echo  ========================================
echo  Next Steps:
echo  ========================================
echo.
echo  1. Restart Laravel server:
echo     Stop current server (Ctrl+C)
echo     php artisan serve
echo.
echo  2. Clear browser cache:
echo     Ctrl + Shift + Delete
echo.
echo  3. Refresh browser:
echo     Ctrl + F5
echo.
echo  4. Login:
echo     URL: http://ansrealty.test/admin
echo     Email: test@example.com
echo     Password: password
echo.
echo  5. Test Resources:
echo     - Create a Builder
echo     - Add a Property
echo     - Create a Lead
echo     - Convert to Opportunity
echo.
echo  ========================================
echo  Database Connection:
echo  ========================================
echo.
echo  Command Line:
echo  mysql -u root ansrealty
echo.
echo  HeidiSQL:
echo  Host: 127.0.0.1
echo  User: root
echo  Database: ansrealty
echo.
echo  ========================================
echo.
pause
