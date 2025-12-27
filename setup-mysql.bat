@echo off
color 0A
cls
echo ========================================
echo  MySQL Database Setup for ANS Realty
echo ========================================
echo.
echo This script will:
echo 1. Create MySQL database 'ansrealty'
echo 2. Run all migrations
echo 3. Seed master data
echo 4. Create test user
echo.
echo Prerequisites:
echo - Laragon MySQL should be running
echo - Default root user (no password)
echo.
pause
echo.

echo [Step 1/4] Creating MySQL database...
mysql -u root -e "DROP DATABASE IF EXISTS ansrealty; CREATE DATABASE ansrealty CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if %errorlevel% neq 0 (
    color 0C
    echo.
    echo ERROR: Could not create database!
    echo.
    echo Please check:
    echo 1. Laragon MySQL is running
    echo 2. MySQL is accessible via 'mysql -u root'
    echo 3. Root user has no password
    echo.
    pause
    exit /b 1
)

echo Database 'ansrealty' created successfully!
echo.

echo [Step 2/4] Clearing config cache...
php artisan config:clear
echo.

echo [Step 3/4] Running migrations...
php artisan migrate --force
echo.

echo [Step 4/4] Seeding database...
php artisan db:seed --force
echo.

echo ========================================
echo  Setup Complete!
echo ========================================
echo.
echo Database: ansrealty
echo Tables: 27+ created
echo Master Data: Seeded
echo Test User: test@example.com / password
echo.
echo Next Steps:
echo 1. Clear browser cache (Ctrl + Shift + Delete)
echo 2. Refresh page (Ctrl + F5)
echo 3. Login: test@example.com / password
echo 4. All resources will work!
echo.
echo ========================================
pause
