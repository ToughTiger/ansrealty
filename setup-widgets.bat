@echo off
echo Creating Filament Widgets...
echo.

REM Create Widgets directory
if not exist "app\Filament\Widgets" (
    mkdir "app\Filament\Widgets"
    echo ✓ Created Widgets directory
) else (
    echo ✓ Widgets directory exists
)

echo.
echo Widget directory structure ready!
echo Next: Create widget files manually or via Artisan
pause
