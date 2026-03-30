<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskOverdue extends Notification
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable): MailMessage
    {
        $relatedTo = $this->getRelatedEntity();
        $overdueHours = now()->diffInHours($this->task->due_date);

        return (new MailMessage)
            ->subject('⚠️ Overdue Task Alert: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have an overdue task that requires immediate attention.')
            ->line('**Task Details:**')
            ->line('📋 **Title:** ' . $this->task->title)
            ->line('⏰ **Was Due:** ' . $this->task->due_date->format('d M Y, h:i A'))
            ->line('⚠️ **Overdue By:** ' . $overdueHours . ' hours')
            ->line('🔥 **Priority:** ' . $this->task->priority)
            ->line('🔗 **Related To:** ' . $relatedTo)
            ->line('📝 **Description:**')
            ->line($this->task->description)
            ->action('View Task', url('/admin/tasks'))
            ->line('Please complete this task as soon as possible.')
            ->salutation('Best regards, ' . config('app.name'));
    }

    public function toArray($notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'due_date' => $this->task->due_date->toDateTimeString(),
            'overdue_hours' => now()->diffInHours($this->task->due_date),
            'message' => "Task overdue: {$this->task->title}",
        ];
    }

    protected function getRelatedEntity(): string
    {
        if (!$this->task->taskable) return 'N/A';

        $type = class_basename($this->task->taskable_type);
        
        if ($type === 'Lead' && $this->task->taskable) {
            return "Lead: {$this->task->taskable->full_name}";
        }
        
        if ($type === 'Opportunity' && $this->task->taskable) {
            return "Opportunity: {$this->task->taskable->lead->full_name}";
        }

        return $type;
    }
}
