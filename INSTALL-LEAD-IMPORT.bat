@echo off
echo ========================================
echo  Installing Lead Import Package
echo ========================================
echo.

cd /d "%~dp0"

echo Step 1: Installing Laravel Excel...
call composer require maatwebsite/excel

echo.
echo Step 2: Creating storage symlink...
php artisan storage:link

echo.
echo Step 3: Clearing caches...
php artisan optimize:clear
php artisan filament:cache-components

echo.
echo ========================================
echo  ✅ Installation Complete!
echo ========================================
echo.
echo Your system now supports:
echo ==========================
echo.
echo 📥 CSV/Excel Import
echo    - Go to: Admin Panel ^> Leads
echo    - Click: "Import Leads" button
echo    - Download template first!
echo.
echo 🔗 Meta (Facebook) Webhook
echo    - URL: https://yourdomain.com/api/webhooks/meta-leads
echo    - Verify Token: ansrealty_webhook_token
echo.
echo 🔗 Google Ads Webhook
echo    - URL: https://yourdomain.com/api/webhooks/google-leads
echo.
echo 🔗 Generic Lead API
echo    - URL: https://yourdomain.com/api/leads
echo    - Method: POST
echo.
echo ========================================
echo  📖 Next Steps:
echo ========================================
echo.
echo 1. Read: LEAD-IMPORT-WEBHOOK-GUIDE.md
echo 2. Download CSV template from admin panel
echo 3. Test import with sample data
echo 4. Configure Meta webhook (if using FB ads)
echo 5. Set up Zapier for Google Ads (optional)
echo.
echo Template location:
echo storage\template\lead-import-template.csv
echo.
pause
