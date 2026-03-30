<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'task_type',
        'priority',
        'default_duration_hours',
        'default_assigned_to',
        'category',
        'is_active',
        'checklist_items',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'checklist_items' => 'array',
        'default_duration_hours' => 'integer',
    ];

    public function defaultAssignedUser()
    {
        return $this->belongsTo(User::class, 'default_assigned_to');
    }

    public function createTask(array $additionalData = []): Task
    {
        $dueDate = now()->addHours($this->default_duration_hours ?? 24);

        return Task::create(array_merge([
            'title' => $this->name,
            'description' => $this->description,
            'task_type' => $this->task_type,
            'priority' => $this->priority,
            'due_date' => $dueDate,
            'assigned_to' => $this->default_assigned_to,
            'status' => 'Pending',
        ], $additionalData));
    }
}
