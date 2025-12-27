# Quick Reference - Common Commands

## 🚀 Initial Setup

```bash
# Run complete setup
setup-complete.bat

# OR manually:
composer install
php artisan migrate
php artisan db:seed
php artisan shield:generate --all
```

## 🗄️ Database Commands

```bash
# Fresh migration (WARNING: Deletes all data)
php artisan migrate:fresh --seed

# Rollback last migration
php artisan migrate:rollback

# Check migration status
php artisan migrate:status

# Seed specific seeder
php artisan db:seed --class=LeadSourceSeeder
```

## 🔧 Development Commands

```bash
# Start development server
php artisan serve

# Clear all cache
php artisan optimize:clear

# Generate IDE helper (if installed)
php artisan ide-helper:models
```

## 👥 User & Roles

```bash
# Create super admin user
php artisan shield:super-admin

# Generate Shield permissions
php artisan shield:generate --all

# Install Shield
php artisan shield:install
```

## 📦 Package Commands

```bash
# Publish Filament assets
php artisan filament:assets

# Publish activity log config
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"

# Clear activity log
php artisan activitylog:clean
```

## 🏗️ Code Generation (Phase 2)

```bash
# Create Model
php artisan make:model Lead -m

# Create Filament Resource
php artisan make:filament-resource Lead --generate

# Create Filament Widget
php artisan make:filament-widget LeadsOverview

# Create Policy
php artisan make:policy LeadPolicy --model=Lead

# Create Observer
php artisan make:observer LeadObserver --model=Lead
```

## 📊 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter LeadTest
```

## 🔍 Debugging

```bash
# View logs in real-time
php artisan tail

# Tinker (Laravel REPL)
php artisan tinker

# Example tinker commands:
# Lead::count()
# User::first()
# LeadSource::all()
```

## 📁 File Permissions (if needed)

```bash
# Windows (in Laragon, usually not needed)
# Just ensure storage/ and bootstrap/cache/ exist
```

## 🎨 Filament Specific

```bash
# Create Filament user (admin)
php artisan make:filament-user

# Clear Filament cache
php artisan filament:cache-components
```

## 📦 Export/Import

```bash
# Create export class
php artisan make:export LeadsExport --model=Lead

# Create import class
php artisan make:import LeadsImport --model=Lead
```

## 🔄 Git Commands (Recommended)

```bash
# Initialize git (if not done)
git init

# Create .gitignore (should exist)
# Add and commit
git add .
git commit -m "Phase 1: Database migrations complete"

# Create branch for development
git checkout -b feature/models
```

## 📝 Useful Queries (Tinker)

```php
// Check table structure
Schema::getColumnListing('leads');

// Get all lead sources
LeadSource::pluck('name', 'id');

// Count records
Lead::count();
Opportunity::where('close_status', 'Open')->count();

// Get today's tasks
Task::whereDate('due_date', today())->get();
```

## 🆘 Common Issues & Fixes

### Issue: Migration failed
```bash
# Check database connection
php artisan db:show

# Reset and retry
php artisan migrate:fresh --seed
```

### Issue: Shield not working
```bash
php artisan shield:install --fresh
php artisan shield:generate --all
```

### Issue: Filament not loading
```bash
php artisan filament:upgrade
php artisan optimize:clear
php artisan view:clear
```

### Issue: Permission denied
```bash
# Ensure storage is writable
# In Laragon, usually automatic
```

## 📚 Next Phase Commands (Preview)

```bash
# Phase 2A: Create all models
php artisan make:model Lead
php artisan make:model Opportunity
php artisan make:model LeadSource
# ... etc

# Phase 2B: Create all resources
php artisan make:filament-resource Lead --generate
php artisan make:filament-resource Opportunity --generate
# ... etc

# Phase 3: Create widgets
php artisan make:filament-widget StatsOverview
php artisan make:filament-widget LeadsBySource --chart
```

---

## 🎯 Current Status Checkpoint

**✅ Completed:**
- Database schema designed (13 tables)
- Migrations created
- Seeders created
- Setup scripts created

**⏳ Next Action:**
Run: `setup-complete.bat`

**🔜 After Setup:**
Copilot will create:
1. All Eloquent models
2. Model relationships
3. Filament Resources
4. Permissions & Policies

---

_Quick Reference Guide - ANS Realty CRM_
