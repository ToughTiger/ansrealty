<?php

namespace App\Filament\Pages;

use App\Models\Task;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class TaskCalendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static string $view = 'filament.pages.task-calendar';
    
    protected static ?string $navigationGroup = 'Tasks';
    
    protected static ?string $navigationLabel = 'Task Calendar';
    
    protected static ?int $navigationSort = 21;

    public function getViewData(): array
    {
        $tasks = Task::with(['taskable', 'assignedAgent'])
            ->whereBetween('due_date', [
                now()->startOfMonth()->subWeeks(1),
                now()->endOfMonth()->addWeeks(1)
            ])
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'start' => $task->due_date->toIso8601String(),
                    'backgroundColor' => $this->getTaskColor($task),
                    'borderColor' => $this->getTaskColor($task),
                    'textColor' => '#fff',
                    'url' => route('filament.admin.resources.tasks.edit', $task),
                    'extendedProps' => [
                        'description' => $task->description,
                        'priority' => $task->priority,
                        'status' => $task->status,
                        'assigned_to' => $task->assignedAgent?->name,
                    ],
                ];
            });

        return [
            'tasks' => $tasks->toArray(),
        ];
    }

    protected function getTaskColor(Task $task): string
    {
        if ($task->status === 'Completed') return '#10b981'; // Green
        if ($task->due_date < now() && $task->status !== 'Completed') return '#ef4444'; // Red (overdue)
        
        return match($task->priority) {
            'High' => '#f59e0b', // Orange
            'Medium' => '#3b82f6', // Blue
            'Low' => '#6b7280', // Gray
            default => '#3b82f6',
        };
    }

    public static function canAccess(): bool
    {
        return auth()->check();
    }
}
