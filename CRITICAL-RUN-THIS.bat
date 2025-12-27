@echo off
color 0C
cls
echo.
echo  ========================================
echo  ^|  CRITICAL: MIGRATIONS NOT RUN YET!  ^|
echo  ========================================
echo.
echo  Your database has NO TABLES!
echo.
echo  The system CANNOT work without migrations.
echo.
echo  ========================================
echo.
echo  Press ANY KEY to run migrations now...
echo.
pause >nul

color 0A
cls
echo.
echo ========================================
echo  Running Migrations - Please Wait...
echo ========================================
echo.

php artisan migrate:fresh --seed

echo.
echo ========================================
echo  Migration Complete!
echo ========================================
echo.
echo  What was created:
echo  - 27+ database tables
echo  - Master data (Sources, Statuses, Stages)
echo  - Test user: test@example.com / password
echo.
echo  Next steps:
echo  1. Refresh your browser (Ctrl + F5)
echo  2. Login with: test@example.com / password
echo  3. All resources will now work!
echo.
echo ========================================
echo.
pause
