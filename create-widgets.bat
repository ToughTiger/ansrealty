@echo off
title Create Filament Dashboard Widgets
color 0A

echo ========================================
echo  Creating Filament Dashboard Widgets
echo ========================================
echo.

cd /d "%~dp0"

echo Running widget creation script...
echo.

php create-all-widgets.php

echo.
echo ========================================
echo  Widget Creation Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Run: php artisan serve
echo 2. Visit: http://localhost:8000/admin
echo 3. Check the dashboard for new widgets
echo.
pause
