@echo off
echo ========================================
echo Creating Public Website Frontend
echo ========================================
echo.

echo [1/2] Creating directories...
mkdir "resources\views\layouts" 2>nul
mkdir "resources\views\pages" 2>nul  
mkdir "resources\views\components" 2>nul
mkdir "app\Http\Controllers\Frontend" 2>nul

echo [2/2] Renaming old welcome.blade.php...
ren "resources\views\welcome.blade.php" "welcome-old.blade.php"

echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Next: Create the homepage file manually
echo.
pause
