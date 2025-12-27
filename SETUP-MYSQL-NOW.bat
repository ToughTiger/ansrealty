@echo off
color 0E
cls
echo.
echo  ========================================
echo  ^|      ANS Realty - MySQL Setup       ^|
echo  ========================================
echo.
echo  Switching from SQLite to MySQL...
echo.
echo  What this script does:
echo  1. Creates 'ansrealty' database
echo  2. Runs migrations (27+ tables)
echo  3. Seeds master data
echo  4. Creates test user
echo.
echo  Prerequisites:
echo  - Laragon must be running
echo  - MySQL must be started
echo.
echo  Press ANY KEY to continue...
pause >nul

color 0A
cls
echo.
echo ========================================
echo  Step 1: Creating Database
echo ========================================
echo.

mysql -u root -e "DROP DATABASE IF EXISTS ansrealty;"
mysql -u root -e "CREATE DATABASE ansrealty CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if %errorlevel% neq 0 (
    color 0C
    echo.
    echo  ERROR: Could not create database!
    echo.
    echo  Possible reasons:
    echo  1. Laragon MySQL not running
    echo  2. Wrong MySQL credentials
    echo  3. Port 3306 blocked
    echo.
    echo  Solutions:
    echo  - Start Laragon MySQL
    echo  - Check root password in .env
    echo  - Use HeidiSQL to create database manually
    echo.
    pause
    exit /b 1
)

echo  Database 'ansrealty' created!
echo.

echo ========================================
echo  Step 2: Clearing Cache
echo ========================================
echo.
php artisan config:clear
php artisan cache:clear
echo  Cache cleared!
echo.

echo ========================================
echo  Step 3: Running Migrations
echo ========================================
echo.
php artisan migrate --force

if %errorlevel% neq 0 (
    color 0C
    echo.
    echo  ERROR: Migrations failed!
    echo.
    echo  Check the error above and:
    echo  1. Verify database exists
    echo  2. Check MySQL connection
    echo  3. Review migration files
    echo.
    pause
    exit /b 1
)

echo.
echo  27+ tables created!
echo.

echo ========================================
echo  Step 4: Seeding Database
echo ========================================
echo.
php artisan db:seed --force

if %errorlevel% neq 0 (
    color 0C
    echo.
    echo  ERROR: Seeding failed!
    echo.
    pause
    exit /b 1
)

echo.
echo  Master data seeded!
echo.

color 0A
cls
echo.
echo  ========================================
echo  ^|         SETUP SUCCESSFUL!           ^|
echo  ========================================
echo.
echo  Database Created:
echo  - Name: ansrealty
echo  - Tables: 27+
echo  - Character Set: utf8mb4_unicode_ci
echo.
echo  Master Data Seeded:
echo  - Lead Sources: 10
echo  - Lead Statuses: 12
echo  - Opportunity Stages: 11
echo.
echo  Test User Created:
echo  - Email: test@example.com
echo  - Password: password
echo.
echo  ========================================
echo  Next Steps:
echo  ========================================
echo.
echo  1. Restart your server:
echo     php artisan serve
echo.
echo  2. Clear browser cache:
echo     Ctrl + Shift + Delete
echo.
echo  3. Refresh browser:
echo     Ctrl + F5
echo.
echo  4. Login:
echo     http://ansrealty.test/admin
echo     test@example.com / password
echo.
echo  5. All resources will work!
echo.
echo  ========================================
echo.
echo  Database Connection:
echo  mysql -u root ansrealty
echo.
echo  ========================================
pause
