@echo off
echo ========================================
echo  ANS Realty - Dashboard Beautification
echo ========================================
echo.

cd /d "%~dp0"

echo Clearing all caches...
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan filament:cache-components

echo.
echo ✅ Dashboard Beautified!
echo.
echo Changes Applied:
echo ================================
echo 📱 Visual Enhancements:
echo   ✓ Removed default Filament widgets
echo   ✓ Added beautiful gradient backgrounds
echo   ✓ Enhanced with emoji icons
echo   ✓ Improved color scheme (Blue primary)
echo   ✓ Better typography and spacing
echo.
echo 🎨 Widget Improvements:
echo   ✓ Trophy/Star/Fire icons for top 3 agents
echo   ✓ Auto-refresh (30s to 10min intervals)
echo   ✓ Descriptive headings with emojis
echo   ✓ Larger badges and better colors
echo   ✓ Gradient stat cards
echo.
echo 📊 Widget Order (Priority-based):
echo   0. Stats Overview (4 key metrics)
echo   1. Pipeline Value (revenue forecast)
echo   2. Sales Funnel (conversions)
echo   3. Agent Performance (leaderboard)
echo   4. Today's Site Visits
echo   5. Overdue Tasks
echo   6. Recent Bookings
echo   7. Lead Source Chart
echo   8. Hot Leads
echo   9. Commission Approvals
echo   10. Today's Follow-ups
echo   11. Leads Trend
echo   12. Opportunities Pipeline
echo   13. Property Inventory
echo.
echo ================================
echo  Your dashboard is now stunning!
echo  Visit: http://localhost/admin
echo ================================
echo.
pause
