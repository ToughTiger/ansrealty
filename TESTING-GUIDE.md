# 🧪 Phase 2 Testing Guide

## Quick Start Testing

### Step 1: Generate Resources
```bash
cd C:\laragon\www\ansrealty
generate-resources.bat
```

### Step 2: Clear Cache
```bash
php artisan optimize:clear
php artisan view:clear
php artisan filament:cache-components
```

### Step 3: Start Server
```bash
php artisan serve
```

### Step 4: Access Admin Panel
Visit: `http://localhost:8000/admin`

---

## 🧪 Model Testing (Tinker)

### Test Lead Model
```bash
php artisan tinker
```

```php
// Test Lead creation
$lead = App\Models\Lead::create([
    'full_name' => 'Raj Kumar',
    'mobile' => '9876543210',
    'email' => 'raj@example.com',
    'budget_min' => 4500000,
    'budget_max' => 8000000,
    'preferred_locations' => ['Andheri', 'Bandra'],
    'property_types' => ['Flat', 'Villa'],
    'purchase_intent' => 'Buy',
    'lead_source_id' => 1,
    'lead_status_id' => 1,
    'priority' => 'Hot',
]);

// Test relationships
$lead->leadSource->name;  // Should return source name
$lead->leadStatus->name;  // Should return status name

// Test accessors
$lead->budget_range;  // Should return formatted range
$lead->priority_color;  // Should return color code

// Test scopes
Lead::hot()->count();  // Count hot leads
Lead::unassigned()->count();  // Count unassigned leads
```

### Test Opportunity Model
```php
$opportunity = App\Models\Opportunity::create([
    'lead_id' => $lead->id,
    'title' => 'Andheri 3 BHK Purchase',
    'expected_value' => 7500000,
    'probability' => 60,
    'expected_close_date' => now()->addDays(30),
    'opportunity_stage_id' => 3,
]);

// Check auto-generated number
$opportunity->opportunity_number;  // Should be OPP-000001

// Test relationships
$opportunity->lead->full_name;
$opportunity->opportunityStage->name;

// Test accessors
$opportunity->expected_value_formatted;  // ₹75.00L
```

### Test Property Model
```php
$builder = App\Models\Builder::create([
    'name' => 'Lodha Group',
    'company_name' => 'Lodha Developers Pvt Ltd',
    'rera_number' => 'P51800002345',
    'is_active' => true,
]);

$property = App\Models\Property::create([
    'name' => 'Lodha Amara Tower A 1201',
    'builder_id' => $builder->id,
    'project_name' => 'Lodha Amara',
    'location' => 'Thane West',
    'city' => 'Thane',
    'state' => 'Maharashtra',
    'pincode' => '400607',
    'property_type' => 'Flat',
    'listing_type' => 'Sale',
    'bedrooms' => 3,
    'bathrooms' => 2,
    'parking' => 2,
    'carpet_area' => 1200,
    'price_min' => 11500000,
    'price_max' => 13500000,
    'amenities' => ['Swimming Pool', 'Gym', 'Garden', 'Security'],
    'possession_status' => 'Ready to Move',
    'availability_status' => 'Available',
    'is_active' => true,
]);

// Test relationships
$property->builder->name;  // Lodha Group

// Test accessors
$property->full_address;  // Full formatted address
$property->price_range;  // ₹115.00L - ₹135.00L
$property->configuration;  // 3 BHK | 2 Bath
```

### Test Task Model (Polymorphic)
```php
$task = App\Models\Task::create([
    'title' => 'Follow-up call with Raj',
    'taskable_type' => 'App\Models\Lead',
    'taskable_id' => $lead->id,
    'type' => 'Call',
    'priority' => 'High',
    'status' => 'Pending',
    'due_date' => now()->addHours(2),
]);

// Test polymorphic relationship
$task->taskable->full_name;  // Should return lead name

// Test scopes
Task::pending()->count();
Task::overdue()->count();
Task::dueToday()->count();
```

---

## 🔍 Database Testing

### Check Tables Exist
```bash
php artisan db:table leads
php artisan db:table opportunities
php artisan db:table properties
php artisan db:table lead_sources
```

### Check Seeded Data
```bash
php artisan tinker
```

```php
LeadSource::count();  // Should be 10
LeadStatus::count();  // Should be 9
OpportunityStage::count();  // Should be 12
```

### Check Foreign Keys
```php
// This should work without errors
$lead = Lead::with([
    'leadSource',
    'leadStatus',
    'assignedAgent',
    'opportunities',
    'tasks',
    'siteVisits'
])->first();
```

---

## 🎨 Filament Resource Testing

### Test LeadResource

#### 1. Navigation
- [ ] Click "Leads" in sidebar
- [ ] Badge should show count of new leads
- [ ] Page loads without errors

#### 2. List View
- [ ] Table displays correctly
- [ ] Search works (name, mobile, email)
- [ ] Filters work (Source, Status, Priority, Agent)
- [ ] Sort works on columns
- [ ] Pagination works

#### 3. Create Lead
- [ ] Click "New Lead" button
- [ ] Form displays all sections
- [ ] Personal info fields required validation
- [ ] Mobile number unique validation
- [ ] Budget fields accept numbers
- [ ] Preferred locations (tags) works
- [ ] Property types (checkboxes) works
- [ ] UTM section collapsed by default
- [ ] Submit creates lead
- [ ] Redirects to list page

#### 4. Edit Lead
- [ ] Click edit icon on any lead
- [ ] Form pre-populated with data
- [ ] Can update all fields
- [ ] Save updates record
- [ ] `last_contact_at` auto-updated

#### 5. View Lead
- [ ] Click view icon on any lead
- [ ] All data displayed correctly
- [ ] "Convert to Opportunity" button visible
- [ ] "Create Task" button works
- [ ] Relation managers visible (Opportunities, Tasks, Site Visits)

#### 6. Actions
- [ ] "Call" button opens phone dialer
- [ ] "Convert to Opportunity" redirects to create opportunity page
- [ ] Delete works
- [ ] Bulk delete works

#### 7. Bulk Actions
- [ ] Select multiple leads
- [ ] "Assign to Agent" bulk action works
- [ ] "Update Status" bulk action works

---

## 📊 Widget Testing (After Widget Creation)

### LeadStatsOverview Widget
- [ ] Displays total leads count
- [ ] Shows hot/warm/cold breakdown
- [ ] Shows conversion rate
- [ ] Updates in real-time

---

## 🐛 Common Issues & Fixes

### Issue: "Class LeadSource not found"
```bash
composer dump-autoload
php artisan optimize:clear
```

### Issue: "Table 'leads' doesn't exist"
```bash
php artisan migrate
```

### Issue: "Activity log table missing"
```bash
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan migrate
```

### Issue: "Media library not working"
```bash
composer require spatie/laravel-medialibrary
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider"
php artisan migrate
```

### Issue: "Filament navigation not showing Leads"
```bash
php artisan filament:cache-components
php artisan optimize:clear
# Clear browser cache (Ctrl+Shift+R)
```

### Issue: "Permission denied on Leads"
```bash
php artisan shield:generate --all
# Logout and login again
```

---

## ✅ Success Criteria

### Phase 2A ✅
- [x] Can create Lead in tinker
- [x] All relationships work
- [x] Accessors return correct values
- [x] Scopes filter correctly

### Phase 2B ⏳
- [ ] Can access Leads page in admin panel
- [ ] Can create lead through form
- [ ] Can edit lead
- [ ] Can view lead details
- [ ] Can delete lead
- [ ] Filters work
- [ ] Search works
- [ ] Bulk actions work

---

## 📸 Screenshots to Take

1. Leads list page (table view)
2. Create lead form
3. Edit lead form
4. View lead page
5. Filters applied
6. Bulk actions menu
7. Navigation sidebar showing Leads

---

## 🔄 Continuous Testing

### After Each Resource Generation
```bash
# 1. Clear cache
php artisan optimize:clear

# 2. Check for errors
php artisan about

# 3. Test in browser
# Visit /admin and click new menu item

# 4. Create sample record
# Test create, edit, view, delete
```

---

## 📞 Performance Testing

### Load Testing
```php
// Create 100 leads for testing
factory(Lead::class, 100)->create();

// Check query performance
DB::enableQueryLog();
Lead::with(['leadSource', 'leadStatus', 'assignedAgent'])->paginate(15);
DB::getQueryLog();  // Should be < 5 queries (no N+1)
```

### Page Load Time
- List page should load in < 2 seconds
- Create page should load in < 1 second
- Edit page should load in < 1 second

---

## 🎯 Test Scenarios

### Scenario 1: Complete Lead Flow
1. Create new lead (Facebook source, Hot priority)
2. Assign to agent
3. Update status to "Contacted"
4. Create follow-up task
5. Schedule site visit
6. Convert to opportunity
7. Verify all data propagated correctly

### Scenario 2: Bulk Operations
1. Create 5 leads
2. Select all
3. Bulk assign to same agent
4. Verify all updated
5. Bulk update status
6. Verify all updated

### Scenario 3: Filtering & Search
1. Create leads with different sources
2. Filter by Facebook source
3. Verify only Facebook leads shown
4. Clear filter
5. Search by mobile number
6. Verify correct lead found

---

**🚨 CURRENT TESTING STATUS:**

- [x] Phase 2A Models - Tested in tinker
- [ ] Phase 2B Resources - Pending generation
- [ ] Filament CRUD - Pending testing
- [ ] Relations - Pending creation
- [ ] Widgets - Pending creation

**Next Action:** Run `generate-resources.bat` and test in browser!

---

_Testing Guide - Phase 2_  
_Updated: Models Complete, Resources Pending_
