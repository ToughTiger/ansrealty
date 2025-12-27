<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SiteVisit extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'lead_id',
        'opportunity_id',
        'property_id',
        'assigned_to',
        'scheduled_at',
        'completed_at',
        'status',
        'customer_feedback',
        'customer_rating',
        'agent_notes',
        'follow_up_required',
        'follow_up_date',
        'cancellation_reason',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'customer_rating' => 'integer',
        'follow_up_required' => 'boolean',
        'follow_up_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Scopes
    public function scopePlanned($query)
    {
        return $query->where('status', 'Planned');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'Confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'Cancelled');
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['Planned', 'Confirmed'])
            ->where('scheduled_at', '>=', now());
    }

    public function scopePast($query)
    {
        return $query->where('scheduled_at', '<', now());
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('scheduled_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeRequiresFollowUp($query)
    {
        return $query->where('follow_up_required', true)
            ->whereNotNull('follow_up_date');
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    // Accessors
    public function getIsUpcomingAttribute(): bool
    {
        return in_array($this->status, ['Planned', 'Confirmed']) 
            && $this->scheduled_at->isFuture();
    }

    public function getIsPastAttribute(): bool
    {
        return $this->scheduled_at->isPast();
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'Completed';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Planned' => '#3b82f6',
            'Confirmed' => '#10b981',
            'Completed' => '#8b5cf6',
            'Cancelled' => '#ef4444',
            'No Show' => '#f59e0b',
            default => '#6b7280',
        };
    }
}
