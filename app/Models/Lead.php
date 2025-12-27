<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Lead extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'full_name',
        'mobile',
        'email',
        'alternate_mobile',
        'budget_min',
        'budget_max',
        'preferred_locations',
        'property_types',
        'purchase_intent',
        'lead_source_id',
        'lead_status_id',
        'assigned_to',
        'priority',
        'notes',
        'remarks',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'first_contact_at',
        'last_contact_at',
        'qualified_at',
        'converted_at',
    ];

    protected $casts = [
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'preferred_locations' => 'array',
        'property_types' => 'array',
        'first_contact_at' => 'datetime',
        'last_contact_at' => 'datetime',
        'qualified_at' => 'datetime',
        'converted_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function leadSource(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class);
    }

    public function leadStatus(): BelongsTo
    {
        return $this->belongsTo(LeadStatus::class);
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class);
    }

    public function siteVisits(): HasMany
    {
        return $this->hasMany(SiteVisit::class);
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    // Scopes
    public function scopeHot($query)
    {
        return $query->where('priority', 'Hot');
    }

    public function scopeWarm($query)
    {
        return $query->where('priority', 'Warm');
    }

    public function scopeCold($query)
    {
        return $query->where('priority', 'Cold');
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }

    public function scopeConverted($query)
    {
        return $query->whereNotNull('converted_at');
    }

    public function scopeNotConverted($query)
    {
        return $query->whereNull('converted_at');
    }

    public function scopeContacted($query)
    {
        return $query->whereNotNull('first_contact_at');
    }

    // Accessors
    public function getBudgetRangeAttribute(): ?string
    {
        if (!$this->budget_min && !$this->budget_max) {
            return null;
        }

        if ($this->budget_min && $this->budget_max) {
            return '₹' . number_format($this->budget_min / 100000, 2) . 'L - ₹' . number_format($this->budget_max / 100000, 2) . 'L';
        }

        if ($this->budget_min) {
            return 'From ₹' . number_format($this->budget_min / 100000, 2) . 'L';
        }

        return 'Up to ₹' . number_format($this->budget_max / 100000, 2) . 'L';
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'Hot' => '#ef4444',
            'Warm' => '#f59e0b',
            'Cold' => '#3b82f6',
            default => '#6b7280',
        };
    }

    public function getIsConvertedAttribute(): bool
    {
        return !is_null($this->converted_at);
    }
}
