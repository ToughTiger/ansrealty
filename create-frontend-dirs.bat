@echo off
echo Creating frontend directories...

mkdir "resources\views\layouts" 2>nul
mkdir "resources\views\pages" 2>nul
mkdir "resources\views\components" 2>nul
mkdir "app\Http\Controllers\Frontend" 2>nul

echo Directories created successfully!
echo.
echo Created:
echo - resources\views\layouts
echo - resources\views\pages
echo - resources\views\components
echo - app\Http\Controllers\Frontend
echo.
pause
