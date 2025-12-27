<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PostSale extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'opportunity_id',
        'property_id',
        'customer_id',
        'agreement_date',
        'agreement_number',
        'agreement_value',
        'registration_date',
        'registration_number',
        'loan_required',
        'bank_name',
        'loan_amount',
        'loan_application_date',
        'loan_status',
        'loan_disbursement_date',
        'possession_date',
        'handover_date',
        'customer_satisfaction_rating',
        'customer_feedback',
        'notes',
    ];

    protected $casts = [
        'agreement_date' => 'date',
        'agreement_value' => 'decimal:2',
        'registration_date' => 'date',
        'loan_required' => 'boolean',
        'loan_amount' => 'decimal:2',
        'loan_application_date' => 'date',
        'loan_disbursement_date' => 'date',
        'possession_date' => 'date',
        'handover_date' => 'date',
        'customer_satisfaction_rating' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // Scopes
    public function scopeWithLoan($query)
    {
        return $query->where('loan_required', true);
    }

    public function scopeLoanApproved($query)
    {
        return $query->where('loan_status', 'Approved');
    }

    public function scopeLoanDisbursed($query)
    {
        return $query->where('loan_status', 'Disbursed');
    }

    public function scopePossessionDue($query)
    {
        return $query->whereNotNull('possession_date')
            ->whereNull('handover_date');
    }

    public function scopeHandedOver($query)
    {
        return $query->whereNotNull('handover_date');
    }

    public function scopeHighSatisfaction($query)
    {
        return $query->where('customer_satisfaction_rating', '>=', 4);
    }

    // Accessors
    public function getAgreementValueFormattedAttribute(): ?string
    {
        if (!$this->agreement_value) {
            return null;
        }
        return '₹' . number_format($this->agreement_value / 100000, 2) . 'L';
    }

    public function getLoanAmountFormattedAttribute(): ?string
    {
        if (!$this->loan_amount) {
            return null;
        }
        return '₹' . number_format($this->loan_amount / 100000, 2) . 'L';
    }

    public function getIsHandedOverAttribute(): bool
    {
        return !is_null($this->handover_date);
    }

    public function getLoanStatusColorAttribute(): ?string
    {
        if (!$this->loan_required) {
            return null;
        }

        return match($this->loan_status) {
            'Applied' => '#3b82f6',
            'Approved' => '#10b981',
            'Disbursed' => '#8b5cf6',
            'Rejected' => '#ef4444',
            default => '#6b7280',
        };
    }

    public function getDaysSinceAgreementAttribute(): ?int
    {
        if (!$this->agreement_date) {
            return null;
        }
        return $this->agreement_date->diffInDays(now());
    }
}
