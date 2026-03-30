@echo off
echo ========================================
echo  Webhook System Setup Complete!
echo ========================================
echo.

cd /d "%~dp0"

echo Step 1: Running migrations...
php artisan migrate --path=database/migrations/2024_01_22_000008_create_webhooks_table.php

echo.
echo Step 2: Seeding default webhooks...
php artisan db:seed --class=WebhookSeeder

echo.
echo Step 3: Generating permissions...
php artisan shield:generate --all

echo.
echo Step 4: Clearing caches...
php artisan optimize:clear
php artisan filament:cache-components

echo.
echo ========================================
echo  ✅ Setup Complete!
echo ========================================
echo.
echo 🔗 Your Webhook Management System is Ready!
echo.
echo Access it here:
echo ===============
echo.
echo 1. Login: http://localhost/admin
echo    Email: admin@ansrealty.com
echo    Password: password
echo.
echo 2. Navigate to: Settings ^> Webhooks
echo.
echo 3. Click "Quick Setup Guide" for full instructions
echo.
echo ========================================
echo  📊 Default Webhooks Created:
echo ========================================
echo.
echo 1. 📘 Meta (Facebook) Lead Ads
echo    URL: http://localhost/api/webhooks/meta-leads
echo    Status: Active
echo.
echo 2. 🔍 Google Ads Lead Forms  
echo    URL: http://localhost/api/webhooks/google-leads
echo    Status: Active
echo.
echo 3. 🔗 Generic Lead API
echo    URL: http://localhost/api/leads
echo    Status: Active
echo.
echo ========================================
echo  🚀 What You Can Do Now:
echo ========================================
echo.
echo ✅ View all webhooks in admin panel
echo ✅ Test webhooks with sample data
echo ✅ Copy webhook URLs to clipboard
echo ✅ Monitor success/failure rates
echo ✅ View detailed setup guides
echo ✅ Create custom webhooks
echo.
echo ========================================
echo  📖 Next Steps:
echo ========================================
echo.
echo 1. Read: LEAD-IMPORT-WEBHOOK-GUIDE.md
echo 2. Configure Meta webhook in Facebook Business Manager
echo 3. Set up Zapier for Google Ads (optional)
echo 4. Test CSV import: Admin ^> Leads ^> Import Leads
echo 5. Create your first lead manually
echo.
pause
