<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LandingPage extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'property_id',
        'title',
        'slug',
        'subtitle',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'hero_heading',
        'hero_subheading',
        'hero_image',
        'cta_text',
        'cta_button_text',
        'features',
        'amenities',
        'location_benefits',
        'special_offer_text',
        'form_heading',
        'form_subheading',
        'gallery_images',
        'featured_image',
        'is_active',
        'views_count',
        'leads_count',
        'campaign_source',
    ];

    protected $casts = [
        'features' => 'array',
        'amenities' => 'array',
        'location_benefits' => 'array',
        'gallery_images' => 'array',
        'is_active' => 'boolean',
        'views_count' => 'integer',
        'leads_count' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    // Accessors
    public function getUrlAttribute(): string
    {
        return url('/landing/' . $this->slug);
    }
    
    public function getConversionRateAttribute(): float
    {
        if ($this->views_count == 0) {
            return 0;
        }
        
        return round(($this->leads_count / $this->views_count) * 100, 2);
    }
    
    public function getHeroImageUrlAttribute(): ?string
    {
        if ($this->hero_image) {
            return asset('storage/' . $this->hero_image);
        }
        return null;
    }
    
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        return null;
    }
}
