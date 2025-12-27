<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Commission extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'opportunity_id',
        'property_id',
        'agent_id',
        'deal_value',
        'commission_percentage',
        'gross_commission',
        'split_percentage',
        'net_commission',
        'status',
        'approved_by',
        'approved_at',
        'payment_date',
        'payment_reference',
        'payment_notes',
        'notes',
    ];

    protected $casts = [
        'deal_value' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'gross_commission' => 'decimal:2',
        'split_percentage' => 'decimal:2',
        'net_commission' => 'decimal:2',
        'approved_at' => 'datetime',
        'payment_date' => 'date',
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
        static::saving(function ($commission) {
            if ($commission->deal_value && $commission->commission_percentage) {
                $commission->gross_commission = ($commission->deal_value * $commission->commission_percentage) / 100;
                
                $splitPercentage = $commission->split_percentage ?? 100;
                $commission->net_commission = ($commission->gross_commission * $splitPercentage) / 100;
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

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'Paid');
    }

    public function scopeForAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ]);
    }

    // Accessors
    public function getDealValueFormattedAttribute(): string
    {
        return '₹' . number_format($this->deal_value / 100000, 2) . 'L';
    }

    public function getGrossCommissionFormattedAttribute(): string
    {
        return '₹' . number_format($this->gross_commission, 2);
    }

    public function getNetCommissionFormattedAttribute(): string
    {
        return '₹' . number_format($this->net_commission, 2);
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->status === 'Paid';
    }

    public function getIsApprovedAttribute(): bool
    {
        return in_array($this->status, ['Approved', 'Paid']);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Pending' => '#f59e0b',
            'Approved' => '#3b82f6',
            'Paid' => '#10b981',
            'Cancelled' => '#ef4444',
            default => '#6b7280',
        };
    }
}
