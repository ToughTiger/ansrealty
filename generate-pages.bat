@echo off
echo ========================================
echo Generating Missing Filament Pages
echo ========================================
echo.

echo Generating Negotiation Resource Pages...
php artisan make:filament-page ListNegotiations --resource=NegotiationResource --type=ListRecords
php artisan make:filament-page CreateNegotiation --resource=NegotiationResource --type=CreateRecord
php artisan make:filament-page ViewNegotiation --resource=NegotiationResource --type=ViewRecord
php artisan make:filament-page EditNegotiation --resource=NegotiationResource --type=EditRecord

echo.
echo Generating PostSale Resource Pages...
php artisan make:filament-page ListPostSales --resource=PostSaleResource --type=ListRecords
php artisan make:filament-page CreatePostSale --resource=PostSaleResource --type=CreateRecord
php artisan make:filament-page ViewPostSale --resource=PostSaleResource --type=ViewRecord
php artisan make:filament-page EditPostSale --resource=PostSaleResource --type=EditRecord

echo.
echo ========================================
echo Pages Generated!
echo ========================================
echo.
pause
