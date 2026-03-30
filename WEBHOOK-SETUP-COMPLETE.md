# 🎉 COMPLETE WEBHOOK & LEAD MANAGEMENT SYSTEM

## ✅ What's Been Built for You

### 1. **Webhook Management UI** 🔗
- **Location**: Admin Panel → Settings → Webhooks
- View all webhooks in one place
- Test webhooks with live data
- Monitor success/failure rates
- Copy webhook URLs with one click
- Built-in setup guides

### 2. **CSV/Excel Bulk Import** 📥
- **Location**: Admin Panel → Leads → "Import Leads"
- Download template → Fill → Upload → Done!
- Supports CSV and Excel (.xlsx)
- Auto-creates missing lead sources
- Assigns leads to agents by email

### 3. **Meta (Facebook) Lead Ads** 📘
- **Endpoint**: `/api/webhooks/meta-leads`
- Auto-captures leads from Facebook ads
- Verify token: `ansrealty_webhook_token`
- Status tracking: Total calls, success rate
- View setup guide in admin panel

### 4. **Google Ads Integration** 🔍
- **Endpoint**: `/api/webhooks/google-leads`
- Works with Zapier/Make automation
- Auto-creates leads with "Hot" priority
- Logs all activity

### 5. **Generic Lead API** 🔗
- **Endpoint**: `/api/leads`
- Connect ANY platform (websites, chatbots, portals)
- Full JSON support
- Flexible field mapping

---

## 🚀 Quick Start (5 Minutes)

### Step 1: Run Setup Script
```bash
SETUP-WEBHOOKS.bat
```

This will:
- ✅ Create webhooks table
- ✅ Seed 3 default webhooks
- ✅ Generate permissions
- ✅ Clear caches

### Step 2: Access Admin Panel
1. Go to: `http://localhost/admin`
2. Login:
   - Email: `admin@ansrealty.com`
   - Password: `password`

### Step 3: View Webhooks
1. Navigate to: **Settings → Webhooks**
2. You'll see 3 pre-configured webhooks
3. Click **"Quick Setup Guide"** for detailed instructions

### Step 4: Test CSV Import
1. Go to: **Sales Pipeline → Leads**
2. Click **"Download Template"**
3. Click **"Import Leads"**
4. Upload the template file
5. Success! 🎉

---

## 📊 Webhook Management Features

### Dashboard View
- **Total Calls**: How many times webhook was called
- **Successful Calls**: How many succeeded
- **Failed Calls**: How many failed
- **Success Rate %**: Automatic calculation
- **Last Called**: Timestamp of last activity

### Actions Available
1. **Test Webhook**: Send sample JSON payload
2. **View Setup Guide**: Platform-specific instructions
3. **Edit**: Update webhook configuration
4. **Delete**: Remove webhook

### Filters
- Filter by type: Meta, Google, API, Custom
- Filter by status: Active, Inactive, Testing

---

## 🔗 Meta (Facebook) Setup Guide

### Prerequisites
- Facebook Business Manager account
- Active Facebook Page
- Lead Ad campaign running

### Configuration Steps

#### 1. In Your Admin Panel:
1. Go to: **Settings → Webhooks**
2. Find: **"Meta (Facebook) Lead Ads"**
3. Copy the endpoint URL (click to copy)
4. Note the verify token: `ansrealty_webhook_token`

#### 2. In Facebook Business Manager:
1. Go to: https://business.facebook.com
2. Navigate to: **Business Settings → Webhooks**
3. Click: **"Configure Webhooks"** (select your Page)
4. Add webhook:
   - **Callback URL**: Paste from step 1.3
   - **Verify Token**: `ansrealty_webhook_token`
5. Subscribe to: **`leadgen`** event
6. Click: **"Verify and Save"**

#### 3. Test It:
1. Submit a test lead on your Facebook ad
2. Check: **Admin Panel → Leads**
3. New lead should appear automatically!
4. Check: **Settings → Webhooks** for call statistics

### What Gets Captured:
- ✅ Full Name
- ✅ Mobile Number
- ✅ Email Address
- ✅ Auto-assigned: Source = "Facebook Ads"
- ✅ Auto-assigned: Priority = "Hot"
- ✅ Auto-assigned: Status = "New"

---

## 🔗 Google Ads Setup Guide

### Via Zapier (Recommended)

#### 1. Create Zap:
1. Trigger: **Google Ads → New Lead Form**
2. Action: **Webhooks by Zapier → POST**

#### 2. Configure Webhook:
1. URL: Copy from **Admin → Settings → Webhooks**
   ```
   https://yourdomain.com/api/webhooks/google-leads
   ```
2. Method: **POST**
3. Data: **JSON**

#### 3. Map Fields:
```json
{
  "name": "{{Lead Name}}",
  "phone": "{{Phone Number}}",
  "email": "{{Email Address}}"
}
```

#### 4. Test & Activate:
1. Send test data
2. Check leads appear in admin
3. Turn on Zap!

### Via Make.com (Alternative)
Same process as Zapier:
- Trigger: Google Ads Lead
- Action: HTTP POST request
- URL: From admin panel

---

## 🔗 Generic API Usage

### For Website Forms

#### HTML Form Example:
```html
<form id="leadForm">
  <input type="text" name="full_name" required>
  <input type="tel" name="mobile" required>
  <input type="email" name="email">
  <button type="submit">Submit</button>
</form>

<script>
document.getElementById('leadForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const formData = new FormData(e.target);
  
  const response = await fetch('https://yourdomain.com/api/leads', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      full_name: formData.get('full_name'),
      mobile: formData.get('mobile'),
      email: formData.get('email'),
      lead_source: 'Website Form'
    })
  });
  
  const result = await response.json();
  alert('Lead submitted! ID: ' + result.lead_id);
});
</script>
```

#### cURL Example:
```bash
curl -X POST https://yourdomain.com/api/leads \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "John Doe",
    "mobile": "9876543210",
    "email": "john@example.com",
    "budget_min": 5000000,
    "budget_max": 10000000,
    "preferred_locations": ["Andheri", "Bandra"],
    "property_types": ["Flat", "Villa"],
    "purchase_intent": "Buy",
    "priority": "Hot",
    "lead_source": "Website Form"
  }'
```

#### Response:
```json
{
  "status": "success",
  "lead_id": 123,
  "message": "Lead created successfully"
}
```

### For WhatsApp Chatbots

Use the same API endpoint with your chatbot platform (Twilio, WhatsApp Business API, etc.)

---

## 📥 CSV Import Instructions

### Step-by-Step:

#### 1. Download Template:
- **Admin Panel → Leads → "Download Template"**
- Opens: `lead-import-template.csv`

#### 2. Fill Your Data:

**Required Fields:**
- `full_name` - Customer name
- `mobile` - Phone number

**Optional Fields:**
- `email` - Email address
- `alternate_mobile` - Secondary phone
- `budget_min` - Minimum budget (₹)
- `budget_max` - Maximum budget (₹)
- `preferred_locations` - Comma-separated: "Andheri,Bandra"
- `property_types` - Comma-separated: "Flat,Villa"
- `purchase_intent` - Buy / Rent / Invest
- `priority` - Hot / Warm / Cold
- `lead_source` - Name of source
- `lead_status` - Status name
- `assigned_agent_email` - Agent's email
- `notes` - Customer notes
- `remarks` - Internal remarks

#### 3. Upload & Import:
1. Click **"Import Leads"**
2. Choose your CSV/Excel file
3. Click **"Import"**
4. Success notification appears
5. All leads visible in table!

### Example Row:
```csv
Rahul Sharma,9876543210,rahul@example.com,,5000000,8000000,"Andheri,Bandra","Flat,Villa",Buy,Hot,Facebook Ads,New,agent@ansrealty.com,Looking for 2BHK,Follow up in 2 days
```

---

## 🧪 Testing Webhooks

### Test in Admin Panel:
1. Go to: **Settings → Webhooks**
2. Click: **"Test"** button on any webhook
3. Enter sample JSON:
```json
{
  "full_name": "Test User",
  "mobile": "9876543210",
  "email": "test@example.com"
}
```
4. Click **"Test"**
5. Check: **Leads** table for new entry

### Test with Postman:
1. Create new POST request
2. URL: From admin panel
3. Body → raw → JSON
4. Paste sample payload
5. Send!

### Monitor Logs:
```bash
# View all webhook activity
tail -f storage/logs/laravel.log | grep "Webhook"

# View lead creation logs
tail -f storage/logs/laravel.log | grep "Lead Created"
```

---

## 📊 Monitoring & Analytics

### In Admin Panel:
- **Total Calls**: All webhook invocations
- **Success Rate**: Percentage of successful calls
- **Last Called**: Most recent activity
- **Failed Calls**: Troubleshooting data

### Database Queries:
```sql
-- Recent webhook activity
SELECT * FROM webhooks ORDER BY last_called_at DESC;

-- Recent leads from webhooks
SELECT * FROM leads 
WHERE lead_source_id IN (
  SELECT id FROM lead_sources 
  WHERE name IN ('Facebook Ads', 'Google Ads', 'API')
) 
ORDER BY created_at DESC 
LIMIT 20;

-- Success rate by webhook
SELECT 
  name, 
  total_calls, 
  successful_calls, 
  ROUND((successful_calls / total_calls) * 100, 2) as success_rate
FROM webhooks 
WHERE total_calls > 0;
```

---

## 🔒 Security Recommendations

### 1. API Authentication (Optional)
Add token-based auth to `/api/leads`:
```php
// In routes/api.php
Route::middleware('auth:sanctum')->post('/leads', [LeadWebhookController::class, 'createLead']);
```

### 2. Rate Limiting
Already configured in Laravel:
- 60 requests per minute per IP
- Configurable in `app/Http/Kernel.php`

### 3. HTTPS Only
- Always use HTTPS in production
- Update webhook URLs to `https://`

### 4. Webhook Verification
- Meta webhooks already verify via token
- Consider adding signature verification for others

---

## ❌ Troubleshooting

### "Leads menu not visible"
```bash
php artisan shield:generate --all
php artisan optimize:clear
```

### "Webhook not receiving data"
1. Check firewall rules
2. Verify URL is publicly accessible
3. Check Laravel logs: `storage/logs/laravel.log`
4. Test with cURL locally

### "Import fails silently"
1. Check column names match template exactly
2. Ensure CSV is UTF-8 encoded
3. Check logs for validation errors
4. Verify `maatwebsite/excel` is installed

### "Permission denied errors"
```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache

# Windows (run as admin)
icacls storage /grant Users:(OI)(CI)F /T
```

---

## 📁 Files Created

### Backend:
- ✅ `app/Models/Webhook.php`
- ✅ `app/Imports/LeadsImport.php`
- ✅ `app/Http/Controllers/Api/LeadWebhookController.php`
- ✅ `app/Filament/Resources/WebhookResource.php`
- ✅ `app/Filament/Resources/WebhookResource/Pages/*`
- ✅ `database/migrations/*_create_webhooks_table.php`
- ✅ `database/seeders/WebhookSeeder.php`
- ✅ `routes/api.php`
- ✅ `config/services.php` (updated)

### Frontend:
- ✅ `resources/views/filament/pages/webhook-quick-guide.blade.php`
- ✅ `resources/views/filament/components/download-template-link.blade.php`

### Templates:
- ✅ `storage/template/lead-import-template.csv`

### Documentation:
- ✅ `LEAD-IMPORT-WEBHOOK-GUIDE.md`
- ✅ `WEBHOOK-SETUP-COMPLETE.md` (this file)
- ✅ `SETUP-WEBHOOKS.bat`
- ✅ `INSTALL-LEAD-IMPORT.bat`

---

## 🎯 Summary: What You Can Do Now

### Lead Creation Methods:
1. ✅ **Manual Entry** - Admin creates via form
2. ✅ **CSV Import** - Bulk upload 1000s of leads
3. ✅ **Facebook Ads** - Auto-capture from Meta
4. ✅ **Google Ads** - Via Zapier/Make
5. ✅ **Website Forms** - Via API integration
6. ✅ **WhatsApp** - Via chatbot integration
7. ✅ **Property Portals** - Via API
8. ✅ **Third-Party Systems** - Via generic API

### Management Features:
- ✅ View all webhooks in UI
- ✅ Test webhooks with sample data
- ✅ Monitor success/failure rates
- ✅ Copy URLs with one click
- ✅ Built-in setup guides
- ✅ Edit/delete webhooks
- ✅ Track activity logs

---

## 🚀 Final Steps

1. **Run**: `SETUP-WEBHOOKS.bat`
2. **Login**: `http://localhost/admin`
3. **Navigate**: Settings → Webhooks
4. **Click**: "Quick Setup Guide"
5. **Test**: Import CSV template
6. **Configure**: Facebook webhook (if needed)
7. **Integrate**: Connect your platforms!

---

**🎉 Your complete lead management system is ready! No more manual data entry!**

Need help? Check logs: `storage/logs/laravel.log`
