<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Task extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'title',
        'description',
        'taskable_type',
        'taskable_id',
        'type',
        'priority',
        'status',
        'assigned_to',
        'created_by',
        'due_date',
        'completed_at',
        'completion_notes',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function taskable(): MorphTo
    {
        return $this->morphTo();
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'In Progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    public function scopeOverdue($query)
    {
        return $query->whereIn('status', ['Pending', 'In Progress'])
            ->where('due_date', '<', now());
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today());
    }

    public function scopeDueThisWeek($query)
    {
        return $query->whereBetween('due_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['High', 'Urgent']);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getIsOverdueAttribute(): bool
    {
        if (in_array($this->status, ['Completed', 'Cancelled'])) {
            return false;
        }
        return $this->due_date->isPast();
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'Completed';
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'Urgent' => '#dc2626',
            'High' => '#ef4444',
            'Medium' => '#f59e0b',
            'Low' => '#3b82f6',
            default => '#6b7280',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Pending' => '#f59e0b',
            'In Progress' => '#3b82f6',
            'Completed' => '#10b981',
            'Cancelled' => '#6b7280',
            default => '#6b7280',
        };
    }

    public function getDaysUntilDueAttribute(): ?int
    {
        if ($this->status === 'Completed') {
            return null;
        }
        return now()->diffInDays($this->due_date, false);
    }
}
