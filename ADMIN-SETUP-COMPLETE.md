# Admin Property & Landing Page Management Setup Guide

## Overview
Complete admin panel setup for managing Properties and Landing Pages in ANS Realty CRM.

## Files Created/Modified

### 1. Models
- ✅ **Property Model** - Updated with Media Library support (`app/Models/Property.php`)
  - Added `HasMedia` interface
  - Added `InteractsWithMedia` trait
  - Added `is_hot` and `views_count` fields
  - Added media collections: images, floor_plans, documents

- ✅ **LandingPage Model** - New model (`app/Models/LandingPage.php`)
  - Full media library support
  - SEO meta tags
  - Campaign tracking
  - Conversion rate calculation
  - Media collections: hero_image, gallery, featured_image

### 2. Migrations
- ✅ **Landing Pages Table** (`database/migrations/2025_12_26_230000_create_landing_pages_table.php`)
- ✅ **Add Hot & Views to Properties** (`database/migrations/2025_12_26_230001_add_hot_and_views_to_properties.php`)

### 3. Filament Resources
- ✅ **PropertyResource** - Updated (`app/Filament/Resources/PropertyResource.php`)
  - Fixed form fields to match database schema
  - Added `is_hot` toggle for Hot Properties section
  - Added toggle actions for Featured and Hot
  - Enhanced filters and bulk actions
  - Image upload support via Spatie Media Library

- ✅ **LandingPageResource** - New resource (`app/Filament/Resources/LandingPageResource.php`)
  - Complete CRUD operations
  - SEO meta tags management
  - Hero section customization
  - Features, amenities, location benefits repeaters
  - Campaign tracking and analytics
  - Duplicate landing page action
  - Visit landing page action

## Setup Instructions

### Step 1: Create Missing Directories
Run these commands in your terminal:

```bash
cd C:\laragon\www\ansrealty

# Create LandingPage Pages directory
mkdir app\Filament\Resources\LandingPageResource\Pages
```

### Step 2: Create Filament Page Classes
Create these three files manually:

**File 1: `app/Filament/Resources/LandingPageResource/Pages/ListLandingPages.php`**
```php
<?php

namespace App\Filament\Resources\LandingPageResource\Pages;

use App\Filament\Resources\LandingPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLandingPages extends ListRecords
{
    protected static string $resource = LandingPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
```

**File 2: `app/Filament/Resources/LandingPageResource/Pages/CreateLandingPage.php`**
```php
<?php

namespace App\Filament\Resources\LandingPageResource\Pages;

use App\Filament\Resources\LandingPageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLandingPage extends CreateRecord
{
    protected static string $resource = LandingPageResource::class;
}
```

**File 3: `app/Filament/Resources/LandingPageResource/Pages/EditLandingPage.php`**
```php
<?php

namespace App\Filament\Resources\LandingPageResource\Pages;

use App\Filament\Resources\LandingPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLandingPage extends EditRecord
{
    protected static string $resource = LandingPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('visit')
                ->label('Visit Landing Page')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn () => $this->record->url)
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }
}
```

### Step 3: Run Migrations
```bash
php artisan migrate
```

### Step 4: Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Admin Panel Features

### Property Management
Access: `/admin/properties`

**Features:**
- ✅ Create new properties
- ✅ Edit existing properties
- ✅ Delete or soft delete properties
- ✅ Upload multiple images (up to 20)
- ✅ Upload floor plans
- ✅ Upload documents (RERA, etc.)
- ✅ Mark as Featured (shows in Featured Properties section)
- ✅ Mark as Hot (shows in Hot Properties section with fire icon)
- ✅ Activate/Deactivate properties
- ✅ Set availability status (Available, Sold, Reserved, On Hold)
- ✅ Set possession status (Ready to Move, Under Construction, Upcoming)
- ✅ Bulk actions (Mark Available, Mark Sold, Delete)
- ✅ Advanced filters (Builder, Type, City, Status, Featured, Hot, Active)
- ✅ Search by name, builder, city
- ✅ View property details
- ✅ Track views count

**Form Sections:**
1. Basic Information
2. Location
3. Configuration (BHK, Area, Parking)
4. Pricing
5. Amenities & Features
6. Availability
7. Media (Images, Floor Plans, Documents)

### Landing Page Management
Access: `/admin/landing-pages`

**Features:**
- ✅ Create custom landing pages for ad campaigns
- ✅ Link to specific properties
- ✅ SEO optimization (Meta Title, Description, Keywords)
- ✅ Custom hero section
- ✅ Call-to-action customization
- ✅ Repeater fields for Features, Amenities, Location Benefits
- ✅ Special offer text (Rich Text Editor)
- ✅ Lead form customization
- ✅ Image gallery management
- ✅ Campaign source tracking
- ✅ Analytics (Views, Leads, Conversion Rate)
- ✅ Duplicate landing page feature
- ✅ Visit landing page action
- ✅ Activate/Deactivate pages
- ✅ Bulk activate/deactivate

**Form Sections:**
1. Basic Information (Property link, Title, Slug, Campaign Source)
2. SEO Meta Tags
3. Hero Section
4. Call to Action
5. Property Features (Repeater)
6. Amenities (Repeater)
7. Location Benefits (Repeater)
8. Special Offer
9. Lead Form
10. Gallery
11. Status & Tracking

## Database Schema

### Properties Table (Updated)
```sql
- id
- name
- builder_id
- project_name
- location
- city
- state
- pincode
- rera_number
- property_type
- listing_type
- carpet_area
- built_up_area
- area_unit
- bedrooms
- bathrooms
- balconies
- parking
- floor_number
- total_floors
- price_min
- price_max
- price_unit
- amenities (JSON)
- possession_date
- possession_status
- availability_status
- is_featured
- is_hot (NEW)
- is_active
- description
- views_count (NEW)
- created_at
- updated_at
- deleted_at
```

### Landing Pages Table (New)
```sql
- id
- property_id
- title
- slug (unique)
- subtitle
- meta_title
- meta_description
- meta_keywords
- hero_heading
- hero_subheading
- cta_text
- cta_button_text
- features (JSON)
- amenities (JSON)
- location_benefits (JSON)
- special_offer_text
- form_heading
- form_subheading
- is_active
- views_count
- leads_count
- campaign_source
- created_at
- updated_at
- deleted_at
```

## Usage Examples

### Creating a Featured Property
1. Go to Admin Panel → Properties → Create
2. Fill in all required fields
3. Upload property images
4. Toggle "Featured Property" to ON
5. Toggle "Hot Property" to ON (if applicable)
6. Set availability status to "Available"
7. Make sure "Active" is ON
8. Save

### Creating a Landing Page for Ads
1. Go to Admin Panel → Landing Pages → Create
2. Select the property to promote
3. Enter campaign-friendly title
4. Auto-generated slug can be customized
5. Fill SEO meta tags for better ad performance
6. Customize hero section with compelling copy
7. Upload hero image
8. Add property features using repeater
9. Add amenities
10. Add location benefits
11. Set special offer text
12. Upload gallery images
13. Set campaign source (e.g., "Google Ads")
14. Make sure "Active" is ON
15. Save

The landing page will be accessible at: `/landing/your-slug`

### Bulk Operations
**Properties:**
- Select multiple properties
- Mark all as Available
- Mark all as Sold
- Delete selected

**Landing Pages:**
- Select multiple pages
- Activate all
- Deactivate all
- Delete selected

## Navigation Structure in Admin

```
📊 Dashboard

📁 Inventory
  └── 🏢 Properties [Badge: Available count]

📁 Marketing
  └── 🚀 Landing Pages [Badge: Active count]

📁 CRM
  └── Leads
  └── Opportunities
  └── Site Visits
  └── Tasks
  └── Negotiations
  └── Commissions
  └── Post Sales

📁 Configuration
  └── Builders
  └── Lead Sources
  └── Lead Statuses
  └── Opportunity Stages
```

## Next Steps

1. Create the three Filament page class files mentioned in Step 2
2. Run migrations
3. Clear cache
4. Login to admin panel
5. Test creating a property
6. Test creating a landing page
7. Verify images upload correctly
8. Test all CRUD operations

## Troubleshooting

**Issue: Media library not working**
- Solution: Make sure Spatie Media Library is installed
- Run: `php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider"`
- Run: `php artisan migrate`

**Issue: Landing page not showing**
- Check if `is_active` is set to true
- Clear route cache: `php artisan route:clear`
- Check slug is unique

**Issue: Images not uploading**
- Check storage permissions
- Run: `php artisan storage:link`
- Check `storage/app/public` directory exists

## Complete! 🎉

Your admin panel is now ready to:
- ✅ Manage all properties (Create, Read, Update, Delete, Deactivate)
- ✅ Upload and manage property images
- ✅ Mark properties as Featured or Hot
- ✅ Create custom landing pages for ad campaigns
- ✅ Track landing page performance
- ✅ Manage SEO for landing pages
- ✅ Bulk operations on properties and landing pages
