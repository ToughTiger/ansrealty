# 🗂️ Master Data Setup Guide

## Overview

To create **Leads, Opportunities, Properties, and Bookings**, you need master data tables populated first. This guide explains what master data is required and how to set it up.

---

## 📋 Required Master Data

### **1. Lead Sources** (lead_sources)
**Why needed:** When creating a lead, you must select where it came from

**Available Sources:**
- Website Contact Form
- Facebook Ads
- Google Ads  
- WhatsApp
- Walk-in
- Referral
- Email Campaign
- Instagram
- Property Portal
- Direct Call

**Seeded by:** `LeadSourceSeeder.php`

---

### **2. Lead Statuses** (lead_statuses)
**Why needed:** Track the current stage of each lead

**Available Statuses:**
- New (just captured)
- Contacted (first call made)
- Qualified (meets criteria)
- Site Visit Planned
- Site Visit Done
- Negotiation
- Converted to Opportunity
- Not Interested
- Lost

**Seeded by:** `LeadStatusSeeder.php`

---

### **3. Opportunity Stages** (opportunity_stages)
**Why needed:** Track opportunity progress through sales funnel

**Available Stages (with probability %):**
1. Opportunity Created (10%)
2. Requirement Finalized (20%)
3. Property Shortlisted (30%)
4. Site Visit Scheduled (40%)
5. Site Visit Completed (50%)
6. Price Discussion (60%)
7. Negotiation (70%)
8. Token Amount Paid (80%)
9. Agreement Stage (90%)
10. Registration Stage (95%)
11. Closed Won (100%)
12. Closed Lost (0%)

**Seeded by:** `OpportunityStageSeeder.php`

---

### **4. Builders** (builders)
**Why needed:** Properties must belong to a builder/developer

**Sample Builders Created:**
- Prestige Group
- Brigade Group
- Sobha Limited
- Godrej Properties
- Purva Developers

**Seeded by:** `ComprehensiveSeeder.php`

---

### **5. Users (Employees)** (users)
**Why needed:** Leads and opportunities must be assigned to employees

**Sample Users Created:**
- Admin User (Super Admin)
- Rajesh Kumar (Manager)
- Priya Sharma (Sales Executive)
- Amit Patel (Sales Executive)
- Sneha Reddy (Sales Executive)
- Vikram Singh (Telecaller)

**Seeded by:** `ComprehensiveSeeder.php`

---

### **6. External Agents** (agents)
**Why needed:** Track agent commissions and performance

**Sample Agents Created:**
- Suresh Properties (2% commission)
- Metro Realty Partners (1.5% commission)
- City Homes Agency (2.5% commission)
- Prime Properties (₹50,000 fixed)
- Golden Estates (3% commission)

**Seeded by:** `ComprehensiveSeeder.php`

---

## 🚀 How to Setup Master Data

### **Option 1: Complete Fresh Setup** (Recommended)

Run this script to setup everything from scratch:

```bash
SETUP-MASTER-DATA.bat
```

**What it does:**
1. Clears all caches
2. Runs fresh migrations (drops all tables, recreates)
3. Seeds lead sources (10 sources)
4. Seeds lead statuses (9 statuses)
5. Seeds opportunity stages (12 stages)
6. Seeds sample data (users, agents, builders, properties, leads, opportunities, bookings)

**⚠️ WARNING:** This will **delete all existing data** and start fresh!

---

### **Option 2: Seed Only Master Data** (If DB already exists)

If you want to keep existing data but add master data:

```bash
php artisan db:seed --class=LeadSourceSeeder
php artisan db:seed --class=LeadStatusSeeder
php artisan db:seed --class=OpportunityStageSeeder
```

---

### **Option 3: Add Sample Data** (Optional)

If you want sample leads, opportunities, agents:

```bash
php artisan db:seed --class=ComprehensiveSeeder
```

---

## ✅ Verification Checklist

After running the setup, verify master data exists:

### **Check Lead Sources:**
1. Go to: `/admin/lead-sources` (if resource exists)
2. Or create a new Lead - you should see source dropdown populated
3. Should have 10 options

### **Check Lead Statuses:**
1. Go to: `/admin/lead-statuses` (if resource exists)
2. Or create a new Lead - you should see status dropdown populated
3. Should have 9 options

### **Check Opportunity Stages:**
1. Go to: `/admin/opportunity-stages` (if resource exists)
2. Or create a new Opportunity - you should see stage dropdown populated
3. Should have 12 options

### **Check Builders:**
1. Go to: `/admin/builders`
2. Should see 5 builders listed
3. Can create properties now

### **Check Users:**
1. Go to: `/admin/users`
2. Should see 6 users (1 admin, 1 manager, 3 sales, 1 telecaller)
3. Can assign leads now

### **Check Agents:**
1. Go to: `/admin/agents`
2. Should see 5 external agents
3. Each has commission structure

---

## 🎯 What You Can Do Now

After master data setup:

### **✅ Create Leads:**
- Select from 10 lead sources
- Choose from 9 lead statuses
- Assign to 6 employees or 5 agents
- Set priority (Hot/Warm/Cold)

### **✅ Create Opportunities:**
- Convert from existing leads
- Select from 12 opportunity stages
- Auto-calculate probability
- Track expected value

### **✅ Create Properties:**
- Select from 5 builders
- Multiple property types (Flat/Villa/Plot/Commercial)
- Various locations
- Price ranges

### **✅ Create Bookings:**
- Link to opportunities
- Assign agents
- Track 10-stage workflow
- Auto-calculate commissions

### **✅ View Dashboard:**
- All widgets now show data
- Charts populated
- Analytics available
- Performance metrics

---

## 🔧 Managing Master Data

### **Adding New Lead Sources:**

**Option 1 - Via Database:**
```sql
INSERT INTO lead_sources (name, slug, color, `order`, created_at, updated_at) 
VALUES ('LinkedIn', 'linkedin', '#0077b5', 11, NOW(), NOW());
```

**Option 2 - Create Resource:**
Create `LeadSourceResource.php` in `app/Filament/Resources/` for UI management

---

### **Adding New Lead Statuses:**

```sql
INSERT INTO lead_statuses (name, slug, color, `order`, created_at, updated_at) 
VALUES ('Follow-up Required', 'follow-up', '#f59e0b', 10, NOW(), NOW());
```

---

### **Adding New Opportunity Stages:**

```sql
INSERT INTO opportunity_stages (name, slug, color, probability, `order`, created_at, updated_at) 
VALUES ('Documentation Pending', 'documentation', '#6366f1', 85, 10, NOW(), NOW());
```

---

### **Adding New Builders:**

Go to: `/admin/builders` → Click "New Builder"

Or via database:
```sql
INSERT INTO builders (name, company_name, mobile, email, created_at, updated_at)
VALUES ('DLF Limited', 'DLF Limited', '9876543220', 'contact@dlf.com', NOW(), NOW());
```

---

## 📊 Database Relationships

```
Lead Sources ─────→ Leads ─────→ Opportunities ─────→ Bookings
Lead Statuses ─────↑              ↑                      ↑
                              Opp. Stages              Agents
                                  ↑                      ↑
                              Properties              Users (Employees)
                                  ↑
                              Builders
```

---

## 🚨 Common Issues

### **Issue 1: "No lead sources available"**
**Solution:** Run `php artisan db:seed --class=LeadSourceSeeder`

### **Issue 2: "No opportunity stages found"**
**Solution:** Run `php artisan db:seed --class=OpportunityStageSeeder`

### **Issue 3: "Can't create property - no builders"**
**Solution:** Run `php artisan db:seed --class=ComprehensiveSeeder` or create builders manually

### **Issue 4: "Can't assign lead - no users"**
**Solution:** Create users at `/admin/users` or run ComprehensiveSeeder

### **Issue 5: "Dashboard shows no data"**
**Solution:** Run `SETUP-MASTER-DATA.bat` to create sample data

---

## 🎓 Best Practices

1. **Run master data seeders first** before creating any leads
2. **Don't delete master data** (sources, statuses, stages) - it breaks relationships
3. **Add new options via seeder** so they persist across environments
4. **Use consistent naming** (e.g., "Site Visit Scheduled" not "Site Visit Booked")
5. **Set proper order values** to control dropdown sequence
6. **Use color codes** for visual differentiation in UI

---

## 📝 Login Credentials

After running `SETUP-MASTER-DATA.bat`:

**All users have password:** `password`

| Role | Email | Use For |
|------|-------|---------|
| Admin | admin@ansrealty.com | Full access, system config |
| Manager | rajesh@ansrealty.com | Team management, approvals |
| Sales | priya@ansrealty.com | Lead management, bookings |
| Sales | amit@ansrealty.com | Lead management, bookings |
| Sales | sneha@ansrealty.com | Lead management, bookings |
| Telecaller | vikram@ansrealty.com | Lead capture, calling |

---

## ✨ Quick Start Workflow

After running `SETUP-MASTER-DATA.bat`:

1. **Login** as `admin@ansrealty.com` / `password`
2. **Check Dashboard** - see widgets with sample data
3. **Create New Lead:**
   - Go to Leads → New Lead
   - Fill name, mobile
   - Select source (dropdown populated)
   - Select status (dropdown populated)
   - Assign to employee
   - Save
4. **Convert to Opportunity:**
   - Open lead → Click "Convert to Opportunity"
   - Select stage (dropdown populated)
   - Set expected value
   - Save
5. **Create Booking:**
   - Go to Bookings → New Booking
   - Select opportunity (from dropdown)
   - Fields auto-fill
   - Commission auto-calculates
   - Save

---

## 🎉 You're All Set!

Master data is the foundation of your CRM. With all seeders run, you now have:

✅ 10 Lead Sources  
✅ 9 Lead Statuses  
✅ 12 Opportunity Stages  
✅ 5 Builders  
✅ 6 Users (Employees)  
✅ 5 External Agents  
✅ 20 Sample Properties  
✅ 10 Sample Leads  
✅ 8 Sample Opportunities  
✅ 3 Sample Bookings  

**Everything you need to start using the CRM!** 🚀

---

_Setup Date: January 22, 2026_  
_All master data ready for production use!_
