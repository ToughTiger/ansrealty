# 🖼️ Image Upload Setup - Spatie Media Library

## Quick Install (Recommended)

**Just run this command:**

```bash
install-media-library.bat
```

This will automatically:
- ✅ Install Spatie Media Library package
- ✅ Install Filament Media Library Plugin
- ✅ Publish migrations and config
- ✅ Run migrations
- ✅ Create storage link
- ✅ Clear all caches

---

## Manual Installation (If batch file doesn't work)

### Step 1: Install Packages

```bash
composer require "spatie/laravel-medialibrary:^11.0"
composer require "filament/spatie-laravel-media-library-plugin:^3.2"
```

### Step 2: Publish Migrations & Config

```bash
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-config"
```

### Step 3: Run Migrations

```bash
php artisan migrate
```

### Step 4: Create Storage Link

```bash
php artisan storage:link
```

### Step 5: Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear
```

---

## What's Included After Installation

### Property Management - Image Upload Features

**Property Images:**
- Upload up to 20 high-quality images
- Drag and drop to reorder
- Built-in image editor
- Automatic image optimization
- Aspect ratio: 16:9
- Auto-resize to 1200x800px
- Thumbnail generation (300x300px)

**Floor Plans:**
- Upload PDF or images
- Up to 10 files
- Supports: PDF, JPEG, PNG

**Documents:**
- Upload RERA certificates, brochures
- PDF only
- Up to 10 files

### Landing Page Management - Image Upload Features

**Hero Image:**
- Single large background image
- Built-in image editor
- Aspect ratio: 16:9
- Auto-resize to 1920x1080px (Full HD)

**Property Gallery:**
- Up to 10 gallery images
- Drag to reorder
- Built-in image editor
- Aspect ratio: 4:3
- Auto-resize to 800x600px
- Thumbnail generation

**Featured Property Image:**
- Single image for consultation section
- Built-in image editor
- Aspect ratio: 3:2
- Auto-resize to 800x533px

---

## Admin Panel Usage

### Uploading Property Images

1. Go to **Admin Panel** → **Properties** → Create/Edit
2. Scroll to **Media** section
3. Click **Choose files** or drag & drop
4. Select multiple images at once
5. Drag images to reorder
6. Click **Save**

### Uploading Landing Page Images

1. Go to **Admin Panel** → **Landing Pages** → Create/Edit
2. **Hero Section** - Upload hero background
3. **Gallery & Images** section - Upload gallery and featured image
4. Click **Save**

---

## Image Specifications

### Property Images
- **Format:** JPG, PNG, WebP
- **Recommended Size:** 1200x800px
- **Aspect Ratio:** 16:9
- **Max Size:** 10MB per image
- **Max Files:** 20 images

### Landing Page - Hero Image
- **Format:** JPG, PNG, WebP
- **Recommended Size:** 1920x1080px (Full HD)
- **Aspect Ratio:** 16:9
- **Max Size:** 10MB

### Landing Page - Gallery
- **Format:** JPG, PNG, WebP
- **Recommended Size:** 800x600px
- **Aspect Ratio:** 4:3
- **Max Size:** 5MB per image
- **Max Files:** 10 images

### Floor Plans
- **Format:** PDF, JPG, PNG
- **Max Size:** 10MB per file
- **Max Files:** 10 files

### Documents
- **Format:** PDF only
- **Max Size:** 10MB per file
- **Max Files:** 10 files

---

## Built-in Features

### Image Editor
- Crop
- Rotate
- Flip
- Zoom
- Aspect ratio lock

### Automatic Optimization
- ✅ Automatic resize to optimal dimensions
- ✅ Thumbnail generation
- ✅ Format conversion (if needed)
- ✅ Quality optimization

### Storage
- All images stored in `storage/app/public/media`
- Organized by collection (images, floor_plans, documents, etc.)
- Public access via `/storage/media/`

---

## Troubleshooting

### Issue: "Storage link not found"
**Solution:**
```bash
php artisan storage:link
```

### Issue: "Permission denied when uploading"
**Solution:**
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```
(On Windows, right-click folders → Properties → Security → Full Control)

### Issue: "Media table does not exist"
**Solution:**
```bash
php artisan migrate
```

### Issue: "Images not showing on admin panel"
**Solution:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Issue: "Cannot upload files larger than 2MB"
**Solution:** Update `php.ini`:
```ini
upload_max_filesize = 20M
post_max_size = 20M
```
Then restart web server.

---

## Database Tables Created

After installation, these tables will be created:

### `media` table
Stores all uploaded files with metadata:
- id
- model_type (Property/LandingPage)
- model_id
- uuid
- collection_name
- name
- file_name
- mime_type
- disk
- conversions_disk
- size
- manipulations
- custom_properties
- generated_conversions
- responsive_images
- order_column
- created_at
- updated_at

---

## Configuration

Media Library config is published at:
`config/media-library.php`

You can customize:
- Disk storage location
- Image quality settings
- Max file sizes
- Allowed mime types
- And more...

---

## Testing

### Test Property Image Upload
1. Login to admin panel
2. Go to Properties → Create
3. Fill required fields
4. Upload test image in Media section
5. Save and verify image appears

### Test Landing Page Image Upload
1. Go to Landing Pages → Create
2. Link to a property
3. Upload hero image
4. Upload gallery images
5. Save and verify images appear

---

## Complete! ✅

Your admin panel now supports:
- ✅ Multiple image uploads
- ✅ Image editing and cropping
- ✅ Drag & drop reordering
- ✅ Automatic optimization
- ✅ Thumbnail generation
- ✅ Floor plans & documents upload
- ✅ Landing page galleries

**Next Steps:**
1. Run `install-media-library.bat`
2. Refresh your admin panel
3. Create/Edit a property
4. Upload images in the Media section
5. Done! 🎉
