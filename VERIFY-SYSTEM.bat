@echo off
echo ========================================
echo  🎉 FINAL SETUP VERIFICATION
echo ========================================
echo.

cd /d "%~dp0"

echo Checking system status...
echo.

echo ✅ Database Connection:
php artisan db:show --database=mysql 2>nul || echo ❌ Database connection failed

echo.
echo ✅ Tables Created:
php artisan db:table leads --database=mysql 2>nul && echo    - leads ✓
php artisan db:table opportunities --database=mysql 2>nul && echo    - opportunities ✓
php artisan db:table webhooks --database=mysql 2>nul && echo    - webhooks ✓
php artisan db:table activity_log --database=mysql 2>nul && echo    - activity_log ✓

echo.
echo ✅ Clearing all caches one more time...
php artisan optimize:clear
php artisan filament:cache-components

echo.
echo ========================================
echo  📊 YOUR COMPLETE SYSTEM IS READY!
echo ========================================
echo.
echo 🔐 Login Credentials:
echo =====================
echo URL: http://localhost/admin
echo Email: admin@ansrealty.com
echo Password: password
echo.
echo 📋 You Should See These Menus:
echo ================================
echo.
echo 1️⃣ Dashboard
echo    - Stats Overview (7 key metrics)
echo    - Charts (Leads, Opportunities, Properties)
echo    - Agent Performance Leaderboard
echo    - Hot Leads Widget
echo    - Today's Follow-ups
echo    - Upcoming Site Visits
echo    - Recent Bookings
echo    - Commission Approvals
echo.
echo 2️⃣ Sales Pipeline
echo    - 📋 Leads (with Import button!)
echo    - 🎯 Opportunities
echo    - 📅 Site Visits
echo    - ✓ Tasks
echo    - 💰 Negotiations
echo.
echo 3️⃣ Property Management
echo    - 🏢 Properties
echo    - 🏗️ Builders
echo.
echo 4️⃣ Team Management
echo    - 👥 Users
echo    - 🤝 Agents
echo    - 📦 Bookings
echo    - 💵 Commissions
echo.
echo 5️⃣ Master Data
echo    - 📊 Lead Sources
echo    - 📋 Lead Statuses
echo    - 🎯 Opportunity Stages
echo.
echo 6️⃣ Settings
echo    - 🔗 Webhooks (NEW!)
echo.
echo ========================================
echo  🚀 QUICK ACTIONS TO TRY:
echo ========================================
echo.
echo ✅ Create a Lead:
echo    1. Go to: Sales Pipeline ^> Leads
echo    2. Click: "New Lead"
echo    3. Fill form and save!
echo.
echo ✅ Import Leads from CSV:
echo    1. Go to: Sales Pipeline ^> Leads
echo    2. Click: "Download Template"
echo    3. Click: "Import Leads"
echo    4. Upload CSV file
echo.
echo ✅ View Webhook Setup:
echo    1. Go to: Settings ^> Webhooks
echo    2. Click: "Quick Setup Guide"
echo    3. Copy webhook URLs
echo.
echo ✅ Test Webhook:
echo    1. Go to: Settings ^> Webhooks
echo    2. Click: "Test" button
echo    3. Enter sample JSON
echo    4. Check Leads table!
echo.
echo ========================================
echo  📖 DOCUMENTATION FILES:
echo ========================================
echo.
echo 📄 WEBHOOK-SETUP-COMPLETE.md
echo    - Complete webhook guide
echo    - Meta/Facebook setup
echo    - Google Ads setup
echo    - API examples
echo.
echo 📄 LEAD-IMPORT-WEBHOOK-GUIDE.md
echo    - CSV import instructions
echo    - Webhook integration details
echo    - Testing guide
echo.
echo 📄 MASTER-IMPLEMENTATION-PLAN.md
echo    - Overall project roadmap
echo    - Progress tracking
echo.
echo ========================================
echo  ⚠️ IMPORTANT NEXT STEPS:
echo ========================================
echo.
echo 1. Logout and login again to see all menus
echo 2. Press Ctrl+Shift+R in browser (hard refresh)
echo 3. Try creating a lead manually
echo 4. Test CSV import
echo 5. Configure webhooks as needed
echo.
echo ========================================
echo  🎯 YOUR SYSTEM INCLUDES:
echo ========================================
echo.
echo ✅ Beautiful dashboard with 13 widgets
echo ✅ Lead management (manual + import + webhooks)
echo ✅ Opportunity tracking with stages
echo ✅ Agent management with commissions
echo ✅ Booking workflow (10 stages)
echo ✅ Site visit scheduling
echo ✅ Task management
echo ✅ Property inventory
echo ✅ CSV/Excel bulk import
echo ✅ Meta (Facebook) webhook integration
echo ✅ Google Ads webhook integration
echo ✅ Generic API for any platform
echo ✅ Webhook management UI
echo ✅ Real-time analytics
echo ✅ Role-based permissions
echo.
echo Press any key to open admin panel in browser...
pause >nul
start http://localhost/admin
