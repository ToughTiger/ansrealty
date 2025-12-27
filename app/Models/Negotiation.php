<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Negotiation extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'opportunity_id',
        'property_id',
        'listed_price',
        'offered_price',
        'counter_offer_price',
        'final_agreed_price',
        'discount_amount',
        'discount_percentage',
        'discount_approved',
        'approved_by',
        'approved_at',
        'booking_amount',
        'booking_date',
        'terms',
        'notes',
    ];

    protected $casts = [
        'listed_price' => 'decimal:2',
        'offered_price' => 'decimal:2',
        'counter_offer_price' => 'decimal:2',
        'final_agreed_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_approved' => 'boolean',
        'approved_at' => 'datetime',
        'booking_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected static function booted()
    {
        static::saving(function ($negotiation) {
            if ($negotiation->listed_price && $negotiation->final_agreed_price) {
                $negotiation->discount_amount = $negotiation->listed_price - $negotiation->final_agreed_price;
                $negotiation->discount_percentage = ($negotiation->discount_amount / $negotiation->listed_price) * 100;
            }
        });
    }

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('discount_approved', true);
    }

    public function scopePendingApproval($query)
    {
        return $query->where('discount_approved', false)
            ->whereNotNull('final_agreed_price');
    }

    public function scopeWithBooking($query)
    {
        return $query->whereNotNull('booking_amount');
    }

    // Accessors
    public function getDiscountAmountFormattedAttribute(): ?string
    {
        if (!$this->discount_amount) {
            return null;
        }
        return '₹' . number_format($this->discount_amount / 100000, 2) . 'L';
    }

    public function getFinalAgreedPriceFormattedAttribute(): ?string
    {
        if (!$this->final_agreed_price) {
            return null;
        }
        return '₹' . number_format($this->final_agreed_price / 100000, 2) . 'L';
    }

    public function getIsApprovedAttribute(): bool
    {
        return $this->discount_approved === true;
    }

    public function getNeedsApprovalAttribute(): bool
    {
        return $this->discount_percentage > 5 && !$this->discount_approved;
    }
}
