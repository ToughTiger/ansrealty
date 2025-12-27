@echo off
echo ========================================
echo Creating Missing Resource Directories
echo ========================================
echo.

echo Creating Negotiation Resource Pages...
mkdir "app\Filament\Resources\NegotiationResource\Pages" 2>nul

echo Creating PostSale Resource Pages...
mkdir "app\Filament\Resources\PostSaleResource\Pages" 2>nul

echo.
echo ========================================
echo Directories Created!
echo ========================================
echo.
echo Next: Run Filament commands to generate pages
echo.
pause
