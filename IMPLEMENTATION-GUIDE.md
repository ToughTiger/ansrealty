# ANS Realty CRM - Setup & Implementation Guide

## 📦 Phase 1: Foundation Complete!

### ✅ What Has Been Created:

#### 1. Database Migrations (13 tables)
- ✅ `lead_sources` - Marketing channels (Website, Facebook, Google, etc.)
- ✅ `lead_statuses` - Lead lifecycle stages
- ✅ `opportunity_stages` - Deal pipeline stages with probability
- ✅ `builders` - Property developers/builders
- ✅ `properties` - **Completely refactored** (20+ new fields)
- ✅ `leads` - Complete lead management (replaces inquiries)
- ✅ `opportunities` - Deal tracking with stages
- ✅ `opportunity_property` - Many-to-many relationship
- ✅ `site_visits` - Visit scheduling & tracking
- ✅ `tasks` - Follow-ups & activities (polymorphic)
- ✅ `negotiations` - Price discussions & discounts
- ✅ `commissions` - Agent commission tracking
- ✅ `post_sales` - Post-closure tracking (agreements, loans, handover)

#### 2. Seeders
- ✅ `LeadSourceSeeder` - 10 pre-configured sources
- ✅ `LeadStatusSeeder` - 9 lead stages
- ✅ `OpportunityStageSeeder` - 12 opportunity stages with probabilities

#### 3. Setup Scripts
- ✅ `setup-complete.bat` - Automated installation script

---

## 🚀 Installation Instructions

### Step 1: Run Setup Script
```bash
# Open Command Prompt in project root and run:
setup-complete.bat
```

This will:
1. Install all required Composer packages
2. Publish configurations
3. Run all migrations
4. Seed initial data
5. Generate Shield permissions

### Alternative: Manual Installation
If the script fails, run these commands manually:

```bash
# Install packages
composer require spatie/laravel-activitylog
composer require maatwebsite/laravel-excel
composer require filament/spatie-laravel-media-library-plugin

# Publish configs
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"

# Run migrations & seed
php artisan migrate
php artisan db:seed

# Generate Shield permissions
php artisan shield:generate --all
```

---

## 📋 Next Steps (What Copilot Will Create Next)

### Phase 2A: Eloquent Models (30 mins)

#### Models to Create:
1. **LeadSource** - Marketing source model
2. **LeadStatus** - Lead status model
3. **OpportunityStage** - Opportunity stage model
4. **Builder** - Property developer model
5. **Lead** - Main lead model (with relationships)
6. **Opportunity** - Opportunity model
7. **SiteVisit** - Site visit model
8. **Task** - Task model (polymorphic)
9. **Negotiation** - Negotiation model
10. **Commission** - Commission model
11. **PostSale** - Post-sales model
12. **Refactor Property & Inquiry models**

#### Features per Model:
- ✅ Fillable fields
- ✅ Casts (JSON, dates, enums)
- ✅ Relationships (belongsTo, hasMany, morphTo)
- ✅ Activity logging trait
- ✅ Soft deletes
- ✅ Scopes & accessors

---

### Phase 2B: Filament Resources (2-3 hours)

#### Resources to Create:
1. **LeadResource**
   - Form: 20+ fields with sections
   - Table: Sortable, filterable, searchable
   - Actions: Convert to Opportunity, Assign Agent
   - Relation Managers: Tasks, Site Visits
   
2. **OpportunityResource**
   - Pipeline view (Kanban board)
   - Stage management
   - Properties attachment
   - Negotiation tracking
   - Commission calculation
   
3. **PropertyResource** (Refactor existing)
   - Complete form with all new fields
   - Image gallery (Media Library)
   - Floor plans upload
   - Availability tracking
   
4. **SiteVisitResource**
   - Calendar view
   - Status tracking
   - Feedback forms
   
5. **TaskResource**
   - My Tasks dashboard
   - Overdue alerts
   - Completion tracking

6. **BuilderResource**
7. **NegotiationResource**
8. **CommissionResource**
9. **PostSaleResource**

---

### Phase 3: Dashboards & Widgets (1 day)

#### Widgets to Create:
1. **Sales Dashboard**
   - Leads by source chart
   - Conversion funnel
   - Revenue trends
   - Monthly closures
   
2. **Agent Performance**
   - Leads assigned vs closed
   - Commission earned
   - Follow-up compliance
   
3. **Quick Stats**
   - Total leads
   - Active opportunities
   - Today's tasks
   - Pending site visits

---

### Phase 4: Automation & Events (1 day)

#### Events & Listeners:
1. `LeadCreated` → Auto-assign agent, Create first task
2. `LeadStatusChanged` → Log activity, Send notification
3. `OpportunityStageChanged` → Update probability, Create tasks
4. `SiteVisitCompleted` → Create follow-up task, Update opportunity
5. `OpportunityClosed` → Calculate commission, Create post-sales record

#### Notifications:
- Email: Welcome email, Task reminders, Stage changes
- SMS: Appointment confirmations (future)
- WhatsApp: Follow-ups (future)

---

### Phase 5: Advanced Features (2-3 days)

1. **Import/Export**
   - Lead CSV import with mapping
   - Property bulk upload
   - Commission reports export
   
2. **API Endpoints**
   - Lead capture API for website forms
   - Webhook receiver (Facebook/Google)
   
3. **Audit Logs**
   - Activity timeline per lead/opportunity
   - User action tracking
   
4. **Permissions & Policies**
   - Role-based access control
   - Field-level permissions
   - Custom gates

---

## 🗂️ Database Schema Overview

### Core Relationships:

```
User (Agent)
  ↓ assigned_to
Lead → Opportunity → Post-Sales
  ↓        ↓            ↓
Tasks  Properties   Commissions
  ↓        ↓
Site Visits → Negotiations
```

### Key Foreign Keys:
- `leads.assigned_to` → `users.id`
- `opportunities.lead_id` → `leads.id`
- `site_visits.opportunity_id` → `opportunities.id`
- `commissions.agent_id` → `users.id`
- `tasks.taskable_id/type` → Polymorphic (Lead, Opportunity)

---

## 📊 Old vs New Structure

### Properties Table - Changes:

**❌ REMOVED:**
- `address` → Split into `location`, `city`, `state`
- `price` → Replaced with `price_min`, `price_max`
- `status` → Now `availability_status`
- `type` → Now `property_type` with more options
- `features` → Now `amenities` (JSON)

**✅ ADDED:**
- Builder relationship
- RERA number
- Carpet/Built-up area
- Bedrooms, bathrooms, parking
- Floor details
- Possession info
- Featured flag
- Soft deletes

### Inquiries → Leads Migration:

**Old `inquiries` table** will be **deprecated**. Data should be migrated:
```sql
INSERT INTO leads (full_name, mobile, email, notes, created_at)
SELECT name, phone, email, message, created_at FROM inquiries;
```

---

## ⚙️ Configuration Files

### Activity Log Config
Location: `config/activitylog.php`

Enable logging on all models:
```php
'log_name' => 'default',
'enabled' => true,
```

### Excel Config
Location: `config/excel.php`

Configure imports/exports batch size, temp directory.

---

## 🔐 Roles & Permissions

### Roles to Create (using Shield):
1. **Super Admin** - Full access
2. **Sales Manager** - View all, manage team
3. **Sales Agent** - Own leads/opportunities only
4. **Telecaller** - Leads + calls only
5. **Accounts** - Commissions read-only
6. **Marketing** - Leads management

### Permissions Structure:
- `view_any_lead`, `view_lead`, `create_lead`, `update_lead`, `delete_lead`
- Same pattern for: Opportunity, Property, Task, etc.

---

## 🎯 Current Status

### ✅ Completed:
- [x] All database migrations created
- [x] Seeders for master data
- [x] Setup automation script
- [x] Documentation

### ⏳ Next Action Required:
**Run the setup script**: `setup-complete.bat`

After successful setup, I will create:
1. All Eloquent models (12 models)
2. Model relationships
3. Filament Resources

---

## 📝 Notes & Best Practices

1. **Data Migration**: Don't delete `inquiries` table until data is migrated
2. **Soft Deletes**: All models use soft deletes - data is never permanently lost
3. **Activity Logging**: Every important action is logged automatically
4. **Polymorphic Tasks**: Tasks can belong to Leads, Opportunities, or Properties
5. **Commission Calculation**: Automatic based on deal value and percentage
6. **Stage Probability**: Opportunity stages have probability for revenue forecasting

---

## 🆘 Troubleshooting

### Migration Errors:
```bash
# Reset database (WARNING: Deletes all data)
php artisan migrate:fresh --seed

# Rollback last batch
php artisan migrate:rollback

# Check migration status
php artisan migrate:status
```

### Package Installation Issues:
```bash
# Clear composer cache
composer clear-cache

# Update composer
composer self-update

# Install with verbose output
composer require <package> -vvv
```

---

## 📞 Ready for Next Phase?

Once you run `setup-complete.bat` successfully, let me know and I'll immediately create:
1. ✅ All 12 Eloquent models
2. ✅ Complete relationships
3. ✅ Activity logging setup
4. ✅ First Filament Resource (LeadResource)

**Estimated total implementation time**: 5-6 weeks of focused development.

---

_Generated by GitHub Copilot CLI - Phase 1 Complete!_
