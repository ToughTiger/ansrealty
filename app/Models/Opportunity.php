<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Opportunity extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'opportunity_number',
        'lead_id',
        'assigned_to',
        'opportunity_stage_id',
        'title',
        'description',
        'expected_value',
        'probability',
        'expected_close_date',
        'final_value',
        'actual_close_date',
        'close_status',
        'lost_reason',
        'lost_remarks',
    ];

    protected $casts = [
        'expected_value' => 'decimal:2',
        'probability' => 'integer',
        'expected_close_date' => 'date',
        'final_value' => 'decimal:2',
        'actual_close_date' => 'date',
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
        static::creating(function ($opportunity) {
            if (!$opportunity->opportunity_number) {
                $opportunity->opportunity_number = 'OPP-' . str_pad(
                    static::withTrashed()->max('id') + 1,
                    6,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function opportunityStage(): BelongsTo
    {
        return $this->belongsTo(OpportunityStage::class);
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'opportunity_property')
            ->withPivot('is_shortlisted', 'notes')
            ->withTimestamps();
    }

    public function shortlistedProperties(): BelongsToMany
    {
        return $this->properties()->wherePivot('is_shortlisted', true);
    }

    public function siteVisits(): HasMany
    {
        return $this->hasMany(SiteVisit::class);
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    public function negotiations(): HasMany
    {
        return $this->hasMany(Negotiation::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    public function postSale(): HasMany
    {
        return $this->hasMany(PostSale::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('close_status', 'Open');
    }

    public function scopeWon($query)
    {
        return $query->where('close_status', 'Won');
    }

    public function scopeLost($query)
    {
        return $query->where('close_status', 'Lost');
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeExpectedClosingThisMonth($query)
    {
        return $query->whereBetween('expected_close_date', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('close_status', 'Open')
            ->where('expected_close_date', '<', now());
    }

    // Accessors
    public function getExpectedValueFormattedAttribute(): ?string
    {
        if (!$this->expected_value) {
            return null;
        }
        return '₹' . number_format($this->expected_value / 100000, 2) . 'L';
    }

    public function getFinalValueFormattedAttribute(): ?string
    {
        if (!$this->final_value) {
            return null;
        }
        return '₹' . number_format($this->final_value / 100000, 2) . 'L';
    }

    public function getIsOverdueAttribute(): bool
    {
        if ($this->close_status !== 'Open') {
            return false;
        }
        return $this->expected_close_date && $this->expected_close_date->isPast();
    }

    public function getDaysToCloseAttribute(): ?int
    {
        if (!$this->expected_close_date || $this->close_status !== 'Open') {
            return null;
        }
        return now()->diffInDays($this->expected_close_date, false);
    }
}
