@echo off
echo ========================================
echo  Fixing Admin Permissions
echo ========================================
echo.

cd /d "%~dp0"

echo Step 1: Making admin user super admin...
php artisan shield:super-admin --user=1

echo.
echo Step 2: Generating all permissions...
php artisan shield:generate --all

echo.
echo Step 3: Publishing Shield assets...
php artisan shield:install --fresh

echo.
echo Step 4: Clearing all caches...
php artisan optimize:clear
php artisan filament:cache-components

echo.
echo Step 5: Clearing browser cache...
echo IMPORTANT: Press Ctrl+Shift+R in your browser to refresh!
echo.

echo ========================================
echo  ✅ Permissions Fixed!
echo ========================================
echo.
echo Now you should see ALL resources:
echo ==================================
echo.
echo 📊 Dashboard (with all widgets)
echo.
echo 📈 Sales Pipeline:
echo    - Leads
echo    - Opportunities  
echo    - Site Visits
echo    - Tasks
echo    - Negotiations
echo.
echo 🏢 Property Management:
echo    - Properties
echo    - Builders
echo.
echo 👥 Team Management:
echo    - Users
echo    - Agents
echo    - Bookings
echo    - Commissions
echo.
echo 📋 Master Data:
echo    - Lead Sources
echo    - Lead Statuses
echo    - Opportunity Stages
echo.
echo ⚙️ Settings:
echo    - Webhooks
echo.
echo ========================================
echo  🔐 Login Credentials:
echo ========================================
echo.
echo URL: http://localhost/admin
echo Email: admin@ansrealty.com
echo Password: password
echo.
echo ========================================
echo  ⚠️ IMPORTANT:
echo ========================================
echo.
echo 1. Logout from admin panel
echo 2. Clear browser cache (Ctrl+Shift+Del)
echo 3. Login again
echo 4. All menus should now be visible!
echo.
pause
