@echo off
echo ========================================
echo  Master Data Resources Setup Complete
echo ========================================
echo.

cd /d "%~dp0"

echo Clearing caches...
php artisan optimize:clear
php artisan filament:cache-components

echo.
echo ✅ Master Data Resources Created!
echo.
echo You can now manage:
echo ====================
echo.
echo 📊 Lead Sources
echo    Navigate to: Master Data → Lead Sources
echo    - Add new sources (LinkedIn, Twitter, etc.)
echo    - Edit colors and order
echo    - View lead count per source
echo.
echo 📋 Lead Statuses  
echo    Navigate to: Master Data → Lead Statuses
echo    - Add custom statuses
echo    - Set colors and order
echo    - Track leads per status
echo.
echo 🎯 Opportunity Stages
echo    Navigate to: Master Data → Opportunity Stages
echo    - Add/edit stages
echo    - Set win probability %%
echo    - Customize sales funnel
echo.
echo ========================================
echo  How to Create a Lead:
echo ========================================
echo.
echo 1. Run master data seeder first:
echo    SETUP-MASTER-DATA.bat
echo.
echo 2. Go to: /admin/leads
echo.
echo 3. Click "New Lead"
echo.
echo 4. Fill the form:
echo    - Full Name (required)
echo    - Mobile (required)
echo    - Email
echo    - Budget Range
echo    - Preferred Locations
echo    - Property Types
echo    - Select Lead Source (dropdown)
echo    - Select Lead Status (dropdown)
echo    - Assign to User (dropdown)
echo    - Set Priority (Hot/Warm/Cold)
echo.
echo 5. Click "Create"
echo.
echo ========================================
echo  Next Steps:
echo ========================================
echo.
echo 1. Run: SETUP-MASTER-DATA.bat
echo    (Populates all dropdowns with data)
echo.
echo 2. Login: http://localhost/admin
echo    Email: admin@ansrealty.com
echo    Password: password
echo.
echo 3. Navigate: Leads → New Lead
echo.
echo 4. Create your first lead! 🎉
echo.
pause
