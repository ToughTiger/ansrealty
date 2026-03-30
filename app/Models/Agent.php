<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Agent extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'agent_code',
        'agent_type',
        'name',
        'company_name',
        'email',
        'mobile',
        'alternate_mobile',
        'pan_number',
        'aadhar_number',
        'rera_number',
        'address',
        'city',
        'state',
        'pincode',
        'bank_name',
        'account_number',
        'ifsc_code',
        'account_holder_name',
        'commission_percentage',
        'commission_type',
        'fixed_commission',
        'assigned_employee_id',
        'status',
        'joining_date',
        'notes',
        'documents',
    ];

    protected $casts = [
        'commission_percentage' => 'decimal:2',
        'fixed_commission' => 'decimal:2',
        'documents' => 'array',
        'joining_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($agent) {
            if (!$agent->agent_code) {
                // Get the next ID
                $nextId = static::withTrashed()->max('id') + 1;
                $agent->agent_code = 'ANR-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function assignedEmployee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_employee_id');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'agent_id');
    }

    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class, 'agent_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeExternal($query)
    {
        return $query->where('agent_type', 'External');
    }

    public function scopeInternal($query)
    {
        return $query->where('agent_type', 'Internal');
    }

    public function scopeAssignedTo($query, $employeeId)
    {
        return $query->where('assigned_employee_id', $employeeId);
    }

    // Accessors
    public function getTotalCommissionEarnedAttribute()
    {
        return $this->commissions()->where('status', 'Paid')->sum('net_commission');
    }

    public function getPendingCommissionAttribute()
    {
        return $this->commissions()->whereIn('status', ['Pending', 'Approved'])->sum('net_commission');
    }

    public function getTotalDealsAttribute()
    {
        return $this->opportunities()->where('close_status', 'Won')->count();
    }

    public function getActiveDealsAttribute()
    {
        return $this->opportunities()->where('close_status', 'Open')->count();
    }
}
