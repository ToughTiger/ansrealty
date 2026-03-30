# 📊 Lead Import & Webhook Integration Guide

## ✅ What's Been Set Up

### 1. CSV/Excel Import Feature
- **Location**: Admin Panel → Leads → "Import Leads" button
- **Supports**: CSV and Excel files (.csv, .xls, .xlsx)
- **Template**: Download template from "Download Template" button

### 2. Meta (Facebook) Lead Ads Webhook
- **Endpoint**: `POST https://yourdomain.com/api/webhooks/meta-leads`
- **Verification**: `GET https://yourdomain.com/api/webhooks/meta-leads`
- **Auto-creates**: Leads from Facebook Lead Ads

### 3. Google Ads Webhook
- **Endpoint**: `POST https://yourdomain.com/api/webhooks/google-leads`
- **Auto-creates**: Leads from Google Ads campaigns

### 4. Generic Lead API
- **Endpoint**: `POST https://yourdomain.com/api/leads`
- **Use for**: Any third-party integration (Zapier, Make, custom apps)

---

## 📥 CSV Import Instructions

### Step 1: Download Template
1. Go to **Admin Panel → Leads**
2. Click **"Download Template"** button
3. Open the CSV file in Excel/Google Sheets

### Step 2: Fill Your Data
Template columns:
```
full_name (required)
mobile (required)
email
alternate_mobile
budget_min
budget_max
preferred_locations (comma-separated: "Andheri,Bandra")
property_types (comma-separated: "Flat,Villa")
purchase_intent (Buy/Rent/Invest)
priority (Hot/Warm/Cold)
lead_source
lead_status
assigned_agent_email
notes
remarks
```

### Step 3: Import
1. Click **"Import Leads"** button
2. Upload your CSV/Excel file
3. Click **"Import"**
4. Success notification will appear

### Important Notes:
- ✅ Auto-creates missing lead sources
- ✅ Assigns agents by email
- ✅ Defaults: status="New", priority="Warm"
- ⚠️ Duplicate mobile numbers will fail
- ⚠️ Invalid data rows are skipped (check logs)

---

## 🔗 Meta (Facebook) Lead Ads Integration

### Setup Steps:

#### 1. Configure Webhook in Facebook Business Manager
1. Go to: https://business.facebook.com
2. Navigate to **Business Settings → Webhooks**
3. Click **"Configure Webhooks"** for your Lead Ad page
4. Add webhook URL:
   ```
   https://yourdomain.com/api/webhooks/meta-leads
   ```
5. Set verify token: `ansrealty_webhook_token`
6. Subscribe to `leadgen` events

#### 2. Update Environment Variables
Add to `.env`:
```env
META_WEBHOOK_VERIFY_TOKEN=ansrealty_webhook_token
```

Add to `config/services.php`:
```php
'meta' => [
    'webhook_verify_token' => env('META_WEBHOOK_VERIFY_TOKEN', 'ansrealty_webhook_token'),
],
```

#### 3. Test Webhook
1. Facebook will send verification GET request
2. Our system responds with challenge token
3. Webhook is now active! 🎉

#### 4. How It Works
- User fills Facebook Lead Ad form
- Facebook sends webhook to our system
- Lead auto-created with:
  - ✅ Source: "Facebook Ads"
  - ✅ Status: "New"
  - ✅ Priority: "Hot"
  - ✅ Full name, mobile, email from form

#### 5. View Logs
Check webhook activity:
```bash
tail -f storage/logs/laravel.log
```

---

## 🔗 Google Ads Lead Form Integration

### Webhook URL:
```
POST https://yourdomain.com/api/webhooks/google-leads
```

### Required Fields:
```json
{
  "name": "John Doe",
  "phone": "9876543210",
  "email": "john@example.com" (optional)
}
```

### Setup via Zapier/Make:
1. Create Zap: **Google Ads Lead** → **Webhook POST**
2. Webhook URL: `https://yourdomain.com/api/webhooks/google-leads`
3. Map fields: `name`, `phone`, `email`
4. Test & activate!

### Response:
```json
{
  "status": "success",
  "lead_id": 123
}
```

---

## 🔗 Generic Lead API

### For Third-Party Integrations

#### Endpoint:
```
POST https://yourdomain.com/api/leads
```

#### Request Body:
```json
{
  "full_name": "John Doe",
  "mobile": "9876543210",
  "email": "john@example.com",
  "budget_min": 5000000,
  "budget_max": 8000000,
  "preferred_locations": ["Andheri", "Bandra"],
  "property_types": ["Flat", "Villa"],
  "purchase_intent": "Buy",
  "priority": "Hot",
  "lead_source": "Website Form",
  "notes": "Urgent requirement"
}
```

#### Response:
```json
{
  "status": "success",
  "lead_id": 124,
  "message": "Lead created successfully"
}
```

#### Use Cases:
- ✅ Website contact forms
- ✅ Mobile app submissions
- ✅ Third-party portals (99acres, MagicBricks)
- ✅ Zapier/Make automations
- ✅ WhatsApp chatbot integrations

---

## 🛠️ Installation Requirements

### Install Laravel Excel Package:
```bash
composer require maatwebsite/excel
```

### Publish Config (Optional):
```bash
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"
```

### Create Symlink:
```bash
php artisan storage:link
```

---

## 🧪 Testing

### Test CSV Import:
1. Use provided template with sample data
2. Import via admin panel
3. Check Leads table

### Test Meta Webhook:
```bash
curl -X POST https://yourdomain.com/api/webhooks/meta-leads \
  -H "Content-Type: application/json" \
  -d '{
    "entry": [{
      "changes": [{
        "value": {
          "field_data": [
            {"name": "full_name", "values": ["Test User"]},
            {"name": "phone_number", "values": ["9876543210"]},
            {"name": "email", "values": ["test@example.com"]}
          ]
        }
      }]
    }]
  }'
```

### Test Generic API:
```bash
curl -X POST https://yourdomain.com/api/leads \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "API Test User",
    "mobile": "9876543210",
    "email": "api@example.com",
    "lead_source": "API Test"
  }'
```

---

## 📊 Monitoring

### Check Logs:
```bash
# All logs
tail -f storage/logs/laravel.log

# Only webhook logs
tail -f storage/logs/laravel.log | grep "Webhook"

# Only import logs
tail -f storage/logs/laravel.log | grep "Import"
```

### Database Check:
```sql
-- Recent leads from webhooks
SELECT * FROM leads 
WHERE lead_source_id IN (
  SELECT id FROM lead_sources 
  WHERE name IN ('Facebook Ads', 'Google Ads', 'API')
) 
ORDER BY created_at DESC 
LIMIT 20;
```

---

## 🚀 Next Steps

### Recommended:
1. ✅ Install: `composer require maatwebsite/excel`
2. ✅ Run: `php artisan storage:link`
3. ✅ Test CSV import with template
4. ✅ Set up Meta webhook (if using FB ads)
5. ✅ Set up Zapier integration for Google Ads
6. ✅ Add API key authentication for security

### Future Enhancements:
- 🔐 API authentication (tokens)
- 📧 Email notifications on new leads
- 🤖 Auto-assignment rules
- 📱 WhatsApp API integration
- 🔄 Duplicate detection
- 📈 Import history tracking

---

## ⚠️ Troubleshooting

### "Class LeadsImport not found"
```bash
composer dump-autoload
```

### "Storage link not found"
```bash
php artisan storage:link
```

### "Webhook not receiving data"
- Check firewall/security rules
- Verify webhook URL is publicly accessible
- Check Laravel logs for errors

### "Import fails silently"
- Check column names match template exactly
- Ensure CSV is UTF-8 encoded
- Check Laravel logs for validation errors

---

## 📞 Support

Need help?
- Check logs: `storage/logs/laravel.log`
- Test webhooks with sample data
- Verify database connections
- Check file permissions on storage/template

---

**🎉 You're all set! Your team can now:**
- ✅ Import bulk leads from CSV/Excel
- ✅ Auto-capture Facebook Lead Ads
- ✅ Integrate Google Ads via Zapier
- ✅ Connect any third-party system via API
