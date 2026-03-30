@echo off
echo ========================================
echo  Setup Automation System - Phase 1
echo ========================================
echo.

cd /d "%~dp0"

echo Step 1: Running migrations...
php artisan migrate --path=database/migrations/2024_01_22_000009_create_assignment_rules_table.php

echo.
echo Step 2: Clearing caches...
php artisan optimize:clear
php artisan filament:cache-components

echo.
echo Step 3: Testing observer registration...
php artisan tinker --execute="dd(class_exists('App\Observers\LeadObserver'));"

echo.
echo ========================================
echo  ✅ Phase 1 Complete!
echo ========================================
echo.
echo What's Been Set Up:
echo ===================
echo.
echo ✅ Assignment Rules System
echo    - Round-robin assignment
echo    - Load balancing
echo    - Location-based routing
echo    - Source-based routing
echo    - Priority-based routing
echo.
echo ✅ Lead Observer
echo    - Auto-assignment on lead creation
echo    - Auto-create "Call in 1 hour" task
echo    - Track last activity
echo    - Auto-qualify after 2+ interactions
echo.
echo ✅ Admin UI
echo    - Settings ^> Assignment Rules
echo    - Create/Edit/Test rules
echo    - View assignment statistics
echo.
echo ========================================
echo  📋 NEXT STEPS:
echo ========================================
echo.
echo 1. Login: http://localhost/admin
echo.
echo 2. Go to: Settings ^> Assignment Rules
echo.
echo 3. Create your first rule:
echo    - Click "New Assignment Rule"
echo    - Name: "Sales Team Round Robin"
echo    - Type: Round Robin
echo    - Assign To: Select your sales agents
echo    - Click "Create"
echo.
echo 4. Test it:
echo    - Go to: Sales Pipeline ^> Leads
echo    - Create a new lead
echo    - It should auto-assign to an agent!
echo    - Check task is auto-created!
echo.
echo 5. Check logs:
echo    tail -f storage/logs/laravel.log
echo.
echo ========================================
echo  🎯 What Happens Now:
echo ========================================
echo.
echo When you create a new lead:
echo 1. System checks active assignment rules
echo 2. Finds matching rule based on conditions
echo 3. Assigns lead to next agent in rotation
echo 4. Creates "Initial Contact" task (due in 1 hour)
echo 5. Updates last_activity_at timestamp
echo 6. Logs everything
echo.
echo When you update a lead:
echo 1. Updates last_activity_at
echo 2. Removes stale flag if it was stale
echo 3. Increments interaction count
echo 4. Auto-qualifies after 2+ status changes
echo.
echo ========================================
echo  🚀 Coming Next (Phases 2-5):
echo ========================================
echo.
echo Phase 2: More auto-tasks
echo    - Site visit ^> Follow-up task
echo    - Proposal sent ^> Follow-up in 3 days
echo    - Opportunity stage change ^> Next action task
echo.
echo Phase 3: Email notifications
echo    - Lead assigned ^> Email to agent
echo    - Task overdue ^> Email alert
echo    - Daily digest
echo.
echo Phase 4: Stale lead alerts
echo    - Widget showing stale leads (7+ days)
echo    - Auto-mark as stale (14 days)
echo    - Manager notifications
echo.
echo Phase 5: Smart status updates
echo    - New ^> Contacted (after first call)
echo    - Auto-close lost after 30 days
echo.
echo ========================================
echo.
echo Ready to test! Create a lead now! 🎉
echo.
pause
