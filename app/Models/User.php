<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'mobile',
        'employee_code',
        'joining_date',
        'status',
        'target_monthly',
        'reports_to',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'joining_date' => 'date',
            'target_monthly' => 'decimal:2',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    // Relationships
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reports_to');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(User::class, 'reports_to');
    }

    public function assignedLeads(): HasMany
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    public function assignedOpportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class, 'assigned_to');
    }

    public function assignedAgents(): HasMany
    {
        return $this->hasMany(Agent::class, 'assigned_employee_id');
    }

    public function managedBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'employee_id');
    }

    // Scopes
    public function scopeEmployees($query)
    {
        return $query->whereIn('user_type', ['Employee', 'Manager']);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
}
