@echo off
echo ========================================
echo   ANS Realty - Agent Management Setup
echo ========================================
echo.

echo Step 1: Running fresh migrations...
php artisan migrate:fresh

echo.
echo Step 2: Running base seeders...
php artisan db:seed --class=LeadSourceSeeder
php artisan db:seed --class=LeadStatusSeeder
php artisan db:seed --class=OpportunityStageSeeder

echo.
echo Step 3: Running comprehensive seeder with sample data...
php artisan db:seed --class=ComprehensiveSeeder

echo.
echo Step 4: Clearing all caches...
php artisan optimize:clear

echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Your database is now populated with:
echo - 6 Users (Admin, Manager, 4 Employees)
echo - 5 External Agents
echo - 5 Builders
echo - 20 Properties
echo - 10 Leads
echo - 8 Opportunities
echo - 3 Bookings (different stages)
echo.
echo Login Credentials:
echo Admin: admin@ansrealty.com / password
echo Manager: rajesh@ansrealty.com / password
echo Employee: priya@ansrealty.com / password
echo.
echo Visit: http://localhost:8000/admin
echo.
pause
