<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Booking extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'booking_number',
        'opportunity_id',
        'property_id',
        'customer_lead_id',
        'agent_id',
        'employee_id',
        'booking_stage',
        'property_value',
        'token_amount',
        'token_date',
        'booking_amount',
        'booking_date',
        'agreement_value',
        'agreement_date',
        'agreement_number',
        'registration_date',
        'registration_number',
        'possession_date',
        'agent_commission_percentage',
        'agent_commission_amount',
        'commission_status',
        'commission_paid',
        'commission_paid_date',
        'payment_reference',
        'invoice_generated',
        'invoice_number',
        'notes',
        'payment_milestones',
    ];

    protected $casts = [
        'property_value' => 'decimal:2',
        'token_amount' => 'decimal:2',
        'booking_amount' => 'decimal:2',
        'agreement_value' => 'decimal:2',
        'agent_commission_percentage' => 'decimal:2',
        'agent_commission_amount' => 'decimal:2',
        'commission_paid' => 'decimal:2',
        'token_date' => 'date',
        'booking_date' => 'date',
        'agreement_date' => 'date',
        'registration_date' => 'date',
        'possession_date' => 'date',
        'commission_paid_date' => 'date',
        'invoice_generated' => 'boolean',
        'payment_milestones' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($booking) {
            if (!$booking->booking_number) {
                $booking->booking_number = 'ANR-BK-' . date('Y') . '-' . str_pad(
                    static::withTrashed()->max('id') + 1,
                    6,
                    '0',
                    STR_PAD_LEFT
                );
            }

            // Auto-calculate commission
            if ($booking->property_value && $booking->agent_id) {
                $agent = Agent::find($booking->agent_id);
                if ($agent) {
                    if ($agent->commission_type === 'Percentage') {
                        $booking->agent_commission_percentage = $agent->commission_percentage;
                        $booking->agent_commission_amount = ($booking->property_value * $agent->commission_percentage) / 100;
                    } else {
                        $booking->agent_commission_amount = $agent->fixed_commission;
                    }
                }
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'customer_lead_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Accessors
    public function getCommissionPendingAttribute()
    {
        return $this->agent_commission_amount - $this->commission_paid;
    }

    public function getStageProgressAttribute()
    {
        $stages = [
            'Token Received' => 10,
            'Token Confirmed' => 20,
            'Agreement Pending' => 30,
            'Agreement Signed' => 50,
            'Payment Plan Active' => 60,
            'Registration Pending' => 70,
            'Registration Done' => 85,
            'Possession Pending' => 90,
            'Possession Done' => 95,
            'Completed' => 100,
        ];

        return $stages[$this->booking_stage] ?? 0;
    }
}