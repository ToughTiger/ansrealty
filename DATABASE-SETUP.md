# 🔧 Database Setup Instructions

## Current Issue
```
SQLSTATE[HY000]: General error: 1 no such table: commissions
```

**Reason:** Migrations have not been run yet. All migration files exist, but the database tables haven't been created.

---

## ✅ Solution - Run Migrations

### Option 1: Use the Batch File (Recommended)
Double-click on: **`run-migrations.bat`**

This will:
1. Drop all existing tables
2. Create all fresh tables (20+ tables)
3. Run seeders (Lead Sources, Statuses, Opportunity Stages)
4. Clear all caches
5. Create a test user

---

### Option 2: Manual Commands
Open Command Prompt in the project folder and run:

```bash
# Run fresh migrations
php artisan migrate:fresh

# Run seeders
php artisan db:seed

# Clear cache
php artisan config:clear
php artisan cache:clear
```

---

## 📊 Tables That Will Be Created

### Core Tables (6)
1. `users` - System users (admin, agents)
2. `lead_sources` - Lead source types
3. `lead_statuses` - Lead status workflow
4. `opportunity_stages` - Opportunity pipeline stages
5. `builders` - Developers/Builders master
6. `properties` - Property inventory

### Transaction Tables (7)
7. `leads` - Lead management
8. `opportunities` - Sales opportunities
9. `opportunity_property` - Properties linked to opportunities (pivot)
10. `site_visits` - Property visit tracking
11. `tasks` - Follow-up tasks (polymorphic)
12. `negotiations` - Price negotiation tracking
13. `commissions` - Agent commission tracking
14. `post_sales` - Post-sale management

### System Tables (5)
15. `permissions` - Spatie permissions
16. `roles` - User roles
17. `model_has_permissions` - Permission assignments
18. `model_has_roles` - Role assignments
19. `role_has_permissions` - Role-permission mapping

### Other Tables
20. `cache` - Application cache
21. `jobs` - Queue jobs
22. `failed_jobs` - Failed queue jobs
23. `password_reset_tokens` - Password resets
24. `sessions` - User sessions
25. `personal_access_tokens` - API tokens
26. `activity_log` - Spatie activity logging
27. `media` - Spatie media library

**Total Tables:** 27+

---

## 🌱 Seeders That Will Run

### 1. LeadSourceSeeder
Creates 10 lead sources:
- Website Contact Form
- Facebook Ads
- Google Ads
- Walk-in
- Referral
- WhatsApp
- Phone Call
- Email
- Social Media
- Other

### 2. LeadStatusSeeder
Creates 12 lead statuses:
- New
- Contacted
- Qualified
- Meeting Scheduled
- Presentation Done
- Follow-up
- Negotiation
- Hot
- Warm
- Cold
- Converted
- Lost

### 3. OpportunityStageSeeder
Creates 11 opportunity stages:
- New Opportunity
- Requirement Discussion
- Property Shortlisting
- Site Visit Planned
- Site Visit Completed
- Proposal Sent
- Negotiation
- Token Amount Paid
- Agreement Signed
- Closed Won
- Closed Lost

### 4. Test User
Creates one admin user:
- Email: `test@example.com`
- Password: `password`

---

## ⚠️ Important Notes

### Before Running:
1. **Backup your database** if you have existing data
2. `migrate:fresh` will **DROP ALL TABLES** and recreate them
3. All existing data will be **LOST**

### After Running:
1. You can login with: `test@example.com` / `password`
2. All FilamentPHP resources will work correctly
3. Navigation badges will display properly
4. No more "table doesn't exist" errors

---

## 🧪 Verify Migration Success

After running migrations, check:

```bash
php artisan tinker
```

Then test:
```php
// Check if tables exist
\App\Models\Lead::count()
\App\Models\Opportunity::count()
\App\Models\Property::count()
\App\Models\Commission::count()
\App\Models\Task::count()

// Check seeded data
\App\Models\LeadSource::all()
\App\Models\LeadStatus::all()
\App\Models\OpportunityStage::all()

// Check user
\App\Models\User::first()
```

All should return without errors.

---

## 🐛 If Errors Occur

### Error: "Class not found"
```bash
composer dump-autoload
php artisan config:clear
```

### Error: "Database locked"
Close all database connections and try again.

### Error: "Migration file not found"
Check if all migration files exist in `database/migrations/`

### Error: "Foreign key constraint"
Run `migrate:fresh` instead of `migrate` to drop all tables first.

---

## 🎯 Next Steps After Migration

1. ✅ Run migrations: `run-migrations.bat`
2. ✅ Login to admin panel: `http://localhost:8000/admin`
3. ✅ Create your first:
   - Builder
   - Property
   - Lead
   - Opportunity
   - Site Visit
   - Task
   - Commission

---

## 📞 Quick Commands Reference

```bash
# Fresh migrations (drops all tables)
php artisan migrate:fresh

# Fresh migrations with seeders
php artisan migrate:fresh --seed

# Check migration status
php artisan migrate:status

# Rollback last migration
php artisan migrate:rollback

# Reset all migrations
php artisan migrate:reset

# Run specific seeder
php artisan db:seed --class=LeadSourceSeeder

# Clear all caches
php artisan optimize:clear
```

---

## ✅ Expected Result

After successful migration:
```
Migration table created successfully.
Migrating: 2025_12_24_010000_create_lead_sources_table
Migrated:  2025_12_24_010000_create_lead_sources_table (25.54ms)
Migrating: 2025_12_24_010001_create_lead_statuses_table
Migrated:  2025_12_24_010001_create_lead_statuses_table (21.32ms)
...
Migrating: 2025_12_24_010012_create_post_sales_table
Migrated:  2025_12_24_010012_create_post_sales_table (18.45ms)

Seeding: LeadSourceSeeder
Seeded:  LeadSourceSeeder (15.23ms)
...
Database seeding completed successfully.
```

✅ All resources will now work without errors!

---

_Database Setup Guide_  
_Run: `run-migrations.bat` to get started_
