@echo off
echo ========================================
echo   ANS Realty - Complete System Setup
echo ========================================
echo.

echo Step 1: Running fresh migrations...
php artisan migrate:fresh --force

echo.
echo Step 2: Running base seeders...
php artisan db:seed --class=LeadSourceSeeder
php artisan db:seed --class=LeadStatusSeeder
php artisan db:seed --class=OpportunityStageSeeder

echo.
echo Step 3: Running comprehensive seeder...
php artisan db:seed --class=ComprehensiveSeeder

echo.
echo Step 4: Clearing all caches...
php artisan optimize:clear
php artisan filament:cache-components

echo.
echo ========================================
echo   SETUP COMPLETE!
echo ========================================
echo.
echo Database populated with sample data:
echo   - 6 Employees (Admin, Manager, 3 Sales, 1 Telecaller)
echo   - 5 External Agents
echo   - 5 Builders
echo   - 20 Properties
echo   - 10 Leads
echo   - 8 Opportunities
echo   - 3 Bookings
echo.
echo Login credentials:
echo   Admin:    admin@ansrealty.com / password
echo   Manager:  rajesh@ansrealty.com / password
echo   Employee: priya@ansrealty.com / password
echo.
echo Visit: http://localhost:8000/admin
echo.
echo Check AGENT-SYSTEM-GUIDE.md for complete documentation
echo.
pause
