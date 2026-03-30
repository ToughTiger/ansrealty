<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentRule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'is_active',
        'priority_order',
        'conditions',
        'assigned_users',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'conditions' => 'array',
        'assigned_users' => 'array',
    ];

    public function counter()
    {
        return $this->hasOne(AssignmentCounter::class, 'rule_id');
    }

    public function users()
    {
        if (!$this->assigned_users) {
            return collect();
        }
        
        return \App\Models\User::whereIn('id', $this->assigned_users)->get();
    }
}

class AssignmentCounter extends Model
{
    protected $fillable = [
        'rule_id',
        'last_assigned_user_id',
        'assignment_count',
        'last_assigned_at',
    ];

    protected $casts = [
        'last_assigned_at' => 'datetime',
    ];

    public function rule()
    {
        return $this->belongsTo(AssignmentRule::class, 'rule_id');
    }

    public function lastAssignedUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'last_assigned_user_id');
    }
}
