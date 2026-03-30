<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TaskObserver
{
    public function updated(Task $task): void
    {
        // Manager escalation for overdue high-priority tasks
        if ($task->isDirty('status') && $task->status === 'Pending') {
            if ($task->priority === 'High' && $task->due_date < now()) {
                $this->escalateToManager($task);
            }
        }

        // Auto-generate recurring task instances
        if ($task->isDirty('status') && $task->status === 'Completed' && $task->is_recurring) {
            $this->createNextRecurringTask($task);
        }
    }

    protected function escalateToManager(Task $task): void
    {
        try {
            $agent = $task->assignedAgent;
            if (!$agent || !$agent->reports_to) return;

            $manager = User::find($agent->reports_to);
            if (!$manager) return;

            // Create escalation notification
            $manager->notify(new \App\Notifications\TaskEscalated($task, $agent));

            Log::info("Task {$task->id} escalated to manager {$manager->id}");
        } catch (\Exception $e) {
            Log::error("Failed to escalate task {$task->id}: " . $e->getMessage());
        }
    }

    protected function createNextRecurringTask(Task $task): void
    {
        if (!$task->recurrence_pattern) return;
        if ($task->recurrence_end_date && $task->recurrence_end_date < now()) return;

        try {
            $nextDueDate = $this->calculateNextDueDate($task);

            if (!$nextDueDate) return;

            Task::create([
                'title' => $task->title,
                'description' => $task->description,
                'task_type' => $task->task_type,
                'priority' => $task->priority,
                'due_date' => $nextDueDate,
                'assigned_to' => $task->assigned_to,
                'taskable_type' => $task->taskable_type,
                'taskable_id' => $task->taskable_id,
                'status' => 'Pending',
                'is_recurring' => true,
                'recurrence_pattern' => $task->recurrence_pattern,
                'recurrence_interval' => $task->recurrence_interval,
                'recurrence_days' => $task->recurrence_days,
                'recurrence_end_date' => $task->recurrence_end_date,
                'parent_task_id' => $task->parent_task_id ?? $task->id,
            ]);

            Log::info("Created recurring task from task {$task->id} for {$nextDueDate}");
        } catch (\Exception $e) {
            Log::error("Failed to create recurring task from {$task->id}: " . $e->getMessage());
        }
    }

    protected function calculateNextDueDate(Task $task): ?\Carbon\Carbon
    {
        $interval = $task->recurrence_interval ?? 1;

        switch ($task->recurrence_pattern) {
            case 'daily':
                return $task->due_date->addDays($interval);

            case 'weekly':
                return $task->due_date->addWeeks($interval);

            case 'monthly':
                return $task->due_date->addMonths($interval);

            case 'yearly':
                return $task->due_date->addYears($interval);

            default:
                return null;
        }
    }
}
