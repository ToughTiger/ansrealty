@echo off
echo ========================================
echo  ANS Realty - Complete Master Data Setup
echo ========================================
echo.

cd /d "%~dp0"

echo [STEP 1/5] Clearing all caches...
php artisan optimize:clear
echo.

echo [STEP 2/5] Running fresh migrations...
php artisan migrate:fresh --force
echo.

echo [STEP 3/5] Seeding master data...
echo   - Lead Sources (10 sources)
echo   - Lead Statuses (9 statuses)
echo   - Opportunity Stages (12 stages)
php artisan db:seed --class=LeadSourceSeeder
php artisan db:seed --class=LeadStatusSeeder
php artisan db:seed --class=OpportunityStageSeeder
echo.

echo [STEP 4/5] Seeding sample data...
echo   - 6 Employees (Admin, Manager, 3 Sales, 1 Telecaller)
echo   - 5 External Agents
echo   - 5 Builders
echo   - 20 Properties
echo   - 10 Leads
echo   - 8 Opportunities
echo   - 3 Bookings
php artisan db:seed --class=ComprehensiveSeeder
echo.

echo [STEP 5/5] Publishing Spatie packages...
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan migrate
echo.

echo ========================================
echo  ✅ Setup Complete!
echo ========================================
echo.
echo Master Data Created:
echo ====================
echo.
echo 📊 Lead Sources (10):
echo   - Website Contact Form
echo   - Facebook Ads
echo   - Google Ads
echo   - WhatsApp
echo   - Walk-in
echo   - Referral
echo   - Email Campaign
echo   - Instagram
echo   - Property Portal
echo   - Direct Call
echo.
echo 📋 Lead Statuses (9):
echo   - New
echo   - Contacted
echo   - Qualified
echo   - Site Visit Planned
echo   - Site Visit Done
echo   - Negotiation
echo   - Converted to Opportunity
echo   - Not Interested
echo   - Lost
echo.
echo 🎯 Opportunity Stages (12):
echo   - Opportunity Created (10%%)
echo   - Requirement Finalized (20%%)
echo   - Property Shortlisted (30%%)
echo   - Site Visit Scheduled (40%%)
echo   - Site Visit Completed (50%%)
echo   - Price Discussion (60%%)
echo   - Negotiation (70%%)
echo   - Token Amount Paid (80%%)
echo   - Agreement Stage (90%%)
echo   - Registration Stage (95%%)
echo   - Closed Won (100%%)
echo   - Closed Lost (0%%)
echo.
echo Sample Data Created:
echo ====================
echo.
echo 👥 Users (6):
echo   - admin@ansrealty.com (Admin)
echo   - rajesh@ansrealty.com (Manager)
echo   - priya@ansrealty.com (Sales Executive)
echo   - amit@ansrealty.com (Sales Executive)
echo   - sneha@ansrealty.com (Sales Executive)
echo   - vikram@ansrealty.com (Telecaller)
echo.
echo 🤝 External Agents (5):
echo   - Suresh Properties
echo   - Metro Realty Partners
echo   - City Homes Agency
echo   - Prime Properties
echo   - Golden Estates
echo.
echo 🏗️ Builders (5):
echo   - Prestige Group
echo   - Brigade Group
echo   - Sobha Limited
echo   - Godrej Properties
echo   - Purva Developers
echo.
echo 🏘️ Properties (20):
echo   - Mixed types (Flats, Villas, Plots, Commercial)
echo   - Various locations
echo   - Price range: ₹30L - ₹5Cr
echo.
echo 📊 Leads (10):
echo   - From different sources
echo   - Various priorities (Hot, Warm, Cold)
echo   - Some with agents assigned
echo.
echo 🎯 Opportunities (8):
echo   - Different stages
echo   - Various probabilities
echo   - Expected values
echo.
echo 💼 Bookings (3):
echo   - At different stages
echo   - With commission tracking
echo   - Token to Registration
echo.
echo ========================================
echo  Login Credentials:
echo ========================================
echo.
echo  URL: http://localhost/admin
echo.
echo  All users:
echo    Password: password
echo.
echo  Admin:
echo    Email: admin@ansrealty.com
echo.
echo  Manager:
echo    Email: rajesh@ansrealty.com
echo.
echo  Sales:
echo    Email: priya@ansrealty.com
echo    Email: amit@ansrealty.com
echo    Email: sneha@ansrealty.com
echo.
echo ========================================
echo  You can now:
echo ========================================
echo   ✓ Create new Leads (sources available)
echo   ✓ Create new Opportunities (stages available)
echo   ✓ Create new Properties (builders available)
echo   ✓ Assign to Agents
echo   ✓ Track Bookings
echo   ✓ View Dashboard Analytics
echo.
echo All master data is ready! 🎉
echo.
pause
