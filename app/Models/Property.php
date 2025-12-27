<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Property extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'builder_id',
        'project_name',
        'location',
        'city',
        'state',
        'pincode',
        'rera_number',
        'property_type',
        'listing_type',
        'carpet_area',
        'built_up_area',
        'area_unit',
        'bedrooms',
        'bathrooms',
        'balconies',
        'parking',
        'floor_number',
        'total_floors',
        'price_min',
        'price_max',
        'price_unit',
        'amenities',
        'possession_date',
        'possession_status',
        'availability_status',
        'is_featured',
        'is_hot',
        'is_active',
        'description',
        'views_count',
        'images',
        'floor_plans',
        'documents',
    ];

    protected $casts = [
        'carpet_area' => 'decimal:2',
        'built_up_area' => 'decimal:2',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'balconies' => 'integer',
        'parking' => 'integer',
        'floor_number' => 'integer',
        'total_floors' => 'integer',
        'price_min' => 'decimal:2',
        'price_max' => 'decimal:2',
        'amenities' => 'array',
        'images' => 'array',
        'floor_plans' => 'array',
        'documents' => 'array',
        'possession_date' => 'date',
        'is_featured' => 'boolean',
        'is_hot' => 'boolean',
        'is_active' => 'boolean',
        'views_count' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function builder(): BelongsTo
    {
        return $this->belongsTo(Builder::class);
    }

    public function opportunities(): BelongsToMany
    {
        return $this->belongsToMany(Opportunity::class, 'opportunity_property')
            ->withPivot('is_shortlisted', 'notes')
            ->withTimestamps();
    }

    public function siteVisits(): HasMany
    {
        return $this->hasMany(SiteVisit::class);
    }

    public function negotiations(): HasMany
    {
        return $this->hasMany(Negotiation::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    public function postSales(): HasMany
    {
        return $this->hasMany(PostSale::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('availability_status', 'Available');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    
    public function scopeHot($query)
    {
        return $query->where('is_hot', true);
    }

    public function scopeForSale($query)
    {
        return $query->where('listing_type', 'Sale');
    }

    public function scopeForRent($query)
    {
        return $query->where('listing_type', 'Rent');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('property_type', $type);
    }

    public function scopeInCity($query, string $city)
    {
        return $query->where('city', $city);
    }

    public function scopeInPriceRange($query, $min, $max)
    {
        return $query->where(function($q) use ($min, $max) {
            $q->whereBetween('price_min', [$min, $max])
              ->orWhereBetween('price_max', [$min, $max]);
        });
    }

    public function scopeReadyToMove($query)
    {
        return $query->where('possession_status', 'Ready to Move');
    }

    // Accessors
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->location,
            $this->city,
            $this->state,
            $this->pincode,
        ]);
        
        return implode(', ', $parts);
    }

    public function getPriceRangeAttribute(): ?string
    {
        if (!$this->price_min && !$this->price_max) {
            return null;
        }

        if ($this->price_min && $this->price_max) {
            return '₹' . number_format($this->price_min / 100000, 2) . 'L - ₹' . number_format($this->price_max / 100000, 2) . 'L';
        }

        if ($this->price_min) {
            return 'From ₹' . number_format($this->price_min / 100000, 2) . 'L';
        }

        return 'Up to ₹' . number_format($this->price_max / 100000, 2) . 'L';
    }

    public function getConfigurationAttribute(): ?string
    {
        if (!$this->bedrooms) {
            return null;
        }

        $config = $this->bedrooms . ' BHK';
        
        if ($this->bathrooms) {
            $config .= ' | ' . $this->bathrooms . ' Bath';
        }
        
        if ($this->balconies) {
            $config .= ' | ' . $this->balconies . ' Balcony';
        }

        return $config;
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->availability_status === 'Available' && $this->is_active;
    }
    
    public function getFirstImageAttribute(): ?string
    {
        if ($this->images && is_array($this->images) && count($this->images) > 0) {
            return asset('storage/' . $this->images[0]);
        }
        return asset('images/property-placeholder.jpg');
    }
}
