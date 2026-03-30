@echo off
echo ========================================
echo  ANS REALTY - Phase 2.1 Setup
echo  Analytics Dashboard Installation
echo ========================================
echo.

cd /d "%~dp0"

echo [1/3] Clearing application cache...
php artisan optimize:clear
echo.

echo [2/3] Clearing view cache...
php artisan view:clear
echo.

echo [3/3] Caching routes and config...
php artisan config:cache
php artisan route:cache
echo.

echo ========================================
echo  ✅ Phase 2.1 Setup Complete!
echo ========================================
echo.
echo New Features Available:
echo.
echo 📊 Dashboard Widgets:
echo   ✓ Pipeline Value (revenue forecast)
echo   ✓ Sales Funnel (conversion rates)
echo   ✓ Agent Performance Leaderboard
echo   ✓ Hot Leads (requiring attention)
echo   ✓ Commission Approvals (one-click)
echo   ✓ Overdue Tasks (accountability)
echo   ✓ Today's Site Visits
echo   ✓ Today's Follow-ups
echo   ✓ Recent Bookings (last 7 days)
echo   ✓ Lead Source Chart
echo.
echo 📈 Analytics Page:
echo   Navigate to: /admin/analytics
echo   (Reports → Analytics in sidebar)
echo.
echo ========================================
echo  Access your dashboard:
echo  URL: http://localhost/admin
echo ========================================
echo.
pause
