@echo off
echo ========================================
echo  Quick Fix: Database Column Corrections
echo  Updated: All Remaining Errors
echo ========================================
echo.

cd /d "%~dp0"

echo Clearing all caches...
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear

echo.
echo ✅ Fixed all column name mismatches:
echo.
echo Leads Table:
echo   - lead_priority → priority
echo   - preferred_location → preferred_locations (JSON array)
echo   - property_type → property_types (JSON array)
echo.
echo Tasks Table:
echo   - task_type → type
echo   - task_status → status
echo   - due_date + due_time → due_date (datetime)
echo.
echo Site Visits Table:
echo   - visit_date + visit_time → scheduled_at (datetime)
echo   - visit_status → status
echo   - assignedAgent (relationship)
echo.
echo Opportunities Table:
echo   - stage_id → opportunity_stage_id
echo.
echo PHP Syntax:
echo   - Fixed arrow function → regular closure (multi-line)
echo.
echo ========================================
echo  All 13 widgets are now fixed!
echo  Refresh your browser: /admin
echo ========================================
echo.
pause
