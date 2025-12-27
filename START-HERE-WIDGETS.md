# 🎯 QUICK START - Run This First!

## ⚠️ PowerShell 6+ Not Available

Your system doesn't have PowerShell 6+ (pwsh) installed. You need to create the dashboard widgets manually.

---

## 🚀 Option 1: Use Laravel Artisan (RECOMMENDED - FASTEST)

**Run this in Command Prompt:**

```batch
cd C:\laragon\www\ansrealty
php artisan make:filament-widget LeadStatsOverview --stats-overview
php artisan make:filament-widget OpportunityPipeline --chart
php artisan make:filament-widget TasksDueWidget --table
php artisan make:filament-widget RevenueWidget --stats-overview
php artisan make:filament-widget SiteVisitWidget --table
```

This will create 5 empty widget files in `app\Filament\Widgets\`.

**Then:**
1. Open the guide: `PHASE-2D-WIDGETS-GUIDE.md`
2. Copy-paste the code for each widget into the generated files
3. Clear cache: `php artisan optimize:clear`
4. Visit dashboard: http://localhost:8000/admin

---

## 🚀 Option 2: Use Batch File

**Double-click this file:** `create-widgets.bat`

It will run all the artisan commands automatically.

---

## 🚀 Option 3: Manual Creation

1. Create directory: `app\Filament\Widgets\`
2. Create 5 PHP files (names in guide)
3. Copy code from `PHASE-2D-WIDGETS-GUIDE.md`
4. Clear cache
5. Test dashboard

---

## 📋 Files You Need to Create

1. **LeadStatsOverview.php** - Lead statistics cards
2. **OpportunityPipeline.php** - Pipeline chart
3. **TasksDueWidget.php** - Tasks table
4. **RevenueWidget.php** - Revenue cards
5. **SiteVisitWidget.php** - Site visits table

All code is in: **`PHASE-2D-WIDGETS-GUIDE.md`**

---

## ✅ After Creation

Run these commands:
```bash
php artisan optimize:clear
php artisan filament:cache-components
php artisan serve
```

Visit: http://localhost:8000/admin

---

## 🎯 What You'll Get

**Dashboard will show:**
- 📊 Lead statistics (4 cards)
- 📈 Opportunity pipeline (chart)
- ✅ Today's tasks (table)
- 💰 Revenue & commissions (4 cards)
- 📅 Today's site visits (table)

---

**Estimated Time:** 15-30 minutes

**See full guide:** `PHASE-2D-WIDGETS-GUIDE.md`
