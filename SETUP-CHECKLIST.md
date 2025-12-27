# ✅ Setup Checklist - Run This Step by Step

## 📋 Pre-Setup Verification

- [ ] Laragon is running
- [ ] MySQL is running (check Laragon)
- [ ] Composer is installed (`composer --version`)
- [ ] PHP 8.2+ is installed (`php -v`)
- [ ] `.env` file exists with correct database credentials

---

## 🚀 Step-by-Step Execution

### ✅ Step 1: Verify Environment
```bash
# Check PHP version (must be 8.2+)
php -v

# Check Composer
composer --version

# Check database connection
php artisan db:show
```

**Expected Output:** Database name, connection type, tables count

**If fails:** Update `.env` file with correct database credentials

---

### ✅ Step 2: Run Complete Setup Script
```bash
# Navigate to project directory
cd C:\laragon\www\ansrealty

# Run setup
setup-complete.bat
```

**Watch for:**
- [x] Installing spatie/laravel-activitylog ✅
- [x] Installing maatwebsite/laravel-excel ✅
- [x] Installing filament/spatie-laravel-media-library-plugin ✅
- [x] Publishing configurations ✅
- [x] Running migrations ✅
- [x] Seeding database ✅
- [x] Generating Shield resources ✅

**Expected Time:** 3-5 minutes

**If any step fails:** See troubleshooting section below

---

### ✅ Step 3: Verify Migrations
```bash
# Check migration status
php artisan migrate:status

# Expected: All migrations should show "Ran"
```

**You should see these tables migrated:**
- [x] users
- [x] cache
- [x] jobs
- [x] permission_tables (5 tables from Shield)
- [x] lead_sources
- [x] lead_statuses
- [x] opportunity_stages
- [x] builders
- [x] properties (refactored)
- [x] leads
- [x] opportunities
- [x] opportunity_property
- [x] site_visits
- [x] tasks
- [x] negotiations
- [x] commissions
- [x] post_sales
- [x] activity_log

**Total Expected:** ~25 tables

---

### ✅ Step 4: Verify Seeded Data
```bash
# Open tinker
php artisan tinker

# Run these commands:
\App\Models\User::count()
# Expected: At least 1

DB::table('lead_sources')->count()
# Expected: 10

DB::table('lead_statuses')->count()
# Expected: 9

DB::table('opportunity_stages')->count()
# Expected: 12

exit
```

**If counts are 0:** Run `php artisan db:seed` manually

---

### ✅ Step 5: Create Super Admin User
```bash
php artisan shield:super-admin
```

**You'll be prompted for:**
- Name: `[Your Name]`
- Email: `[your-email@example.com]`
- Password: `[Strong Password]`

**Save these credentials!** You'll need them to login.

---

### ✅ Step 6: Start Development Server
```bash
php artisan serve
```

**Expected Output:**
```
Starting Laravel development server: http://127.0.0.1:8000
```

**Keep this terminal open!**

---

### ✅ Step 7: Access Admin Panel
Open browser and visit:
```
http://localhost:8000/admin
```

**Login with:**
- Email: [your created email]
- Password: [your created password]

**Expected:** Filament dashboard loads with sidebar menu

---

### ✅ Step 8: Verify Installation

**In Admin Panel, check sidebar menu:**
- [x] Dashboard (should be visible)
- [x] Users (should be visible)
- [x] Roles (should be visible)
- [x] Shield section (should be visible)

**At this point:**
- ✅ No Lead/Opportunity/Property menus yet (normal - Phase 2)
- ✅ Only User management and Shield menus visible
- ✅ You should be logged in as Super Admin

---

## ✅ Post-Setup Verification Checklist

Run these commands to verify everything:

```bash
# 1. Check installed packages
composer show spatie/laravel-activitylog
# Should show package info

composer show maatwebsite/laravel-excel
# Should show package info

composer show filament/spatie-laravel-media-library-plugin
# Should show package info

# 2. Check Filament installation
php artisan filament:list-users
# Should list your admin user

# 3. Check permissions
php artisan permission:show
# Should show all generated permissions

# 4. Check database tables
php artisan db:table lead_sources
# Should show 10 records

php artisan db:table opportunity_stages
# Should show 12 records
```

---

## 🆘 Troubleshooting

### ❌ Issue: "Database connection failed"

**Solution:**
```bash
# 1. Check .env file
# Ensure these are correct:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ansrealty
DB_USERNAME=root
DB_PASSWORD=

# 2. Create database manually
# Open phpMyAdmin (via Laragon)
# Create database named: ansrealty

# 3. Retry
php artisan migrate
```

---

### ❌ Issue: "Composer install failed"

**Solution:**
```bash
# Clear composer cache
composer clear-cache

# Update composer
composer self-update

# Retry installation
composer install
```

---

### ❌ Issue: "Migration failed - table already exists"

**Solution:**
```bash
# Option 1: Fresh migration (WARNING: Deletes all data)
php artisan migrate:fresh --seed

# Option 2: Rollback and retry
php artisan migrate:rollback
php artisan migrate
```

---

### ❌ Issue: "Shield commands not found"

**Solution:**
```bash
# Install Shield manually
composer require bezhansalleh/filament-shield

# Publish config
php artisan vendor:publish --tag="filament-shield-config"

# Install Shield
php artisan shield:install

# Generate permissions
php artisan shield:generate --all
```

---

### ❌ Issue: "Filament not loading / 404 error"

**Solution:**
```bash
# Clear all cache
php artisan optimize:clear
php artisan view:clear
php artisan config:clear

# Reinstall Filament assets
php artisan filament:assets

# Restart server
php artisan serve
```

---

### ❌ Issue: "Cannot create super admin"

**Solution:**
```bash
# Manual user creation
php artisan tinker

# Run these:
$user = \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@ansrealty.com',
    'password' => bcrypt('password')
]);

$user->assignRole('super_admin');

exit
```

---

## ✅ Success Criteria

**You've successfully completed setup when:**

- [x] All packages installed (no errors)
- [x] All migrations ran (25+ tables created)
- [x] Seeder data loaded (31 records)
- [x] Super admin user created
- [x] Can login to /admin panel
- [x] See Dashboard, Users, Roles menus
- [x] No PHP errors in terminal
- [x] No browser console errors

---

## 🎯 After Successful Setup

**Report back with:**
```
✅ Setup complete!
✅ Admin panel accessible
✅ Migrations successful
✅ Seeders ran
✅ Ready for Phase 2!
```

**Then I will immediately:**
1. Create all 12 Eloquent models
2. Define all relationships
3. Add activity logging
4. Create first Filament Resource (LeadResource)
5. Test CRUD operations

---

## 📸 Screenshot Checklist

**Take screenshots of:**
1. Terminal showing successful migration
2. Admin panel dashboard (logged in)
3. phpMyAdmin showing all tables

**This helps verify everything worked correctly!**

---

## 📞 Status Check Commands

```bash
# Quick status check (run all at once)
php artisan migrate:status && \
php artisan db:table lead_sources --count && \
php artisan db:table opportunity_stages --count && \
php artisan filament:list-users
```

**Expected output:**
- All migrations: Ran ✅
- Lead sources: 10 ✅
- Opportunity stages: 12 ✅
- Users: 1+ ✅

---

## 🎬 You Are Here

```
┌──────────────────────────────────────┐
│         SETUP PROGRESS               │
├──────────────────────────────────────┤
│ [▸] Step 1: Verify Environment       │
│ [ ] Step 2: Run Setup Script         │
│ [ ] Step 3: Verify Migrations        │
│ [ ] Step 4: Verify Seeded Data       │
│ [ ] Step 5: Create Super Admin       │
│ [ ] Step 6: Start Dev Server         │
│ [ ] Step 7: Access Admin Panel       │
│ [ ] Step 8: Verify Installation      │
└──────────────────────────────────────┘
```

**Current Action:** Run `setup-complete.bat`

---

_Setup Checklist - ANS Realty CRM_  
_Estimated Time: 5-10 minutes_
