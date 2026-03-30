<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskEscalated extends Notification
{
    use Queueable;

    protected $task;
    protected $agent;

    public function __construct(Task $task, User $agent)
    {
        $this->task = $task;
        $this->agent = $agent;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable): MailMessage
    {
        $overdueHours = now()->diffInHours($this->task->due_date);

        return (new MailMessage)
            ->subject('🚨 ESCALATION: Overdue High-Priority Task')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('⚠️ **A high-priority task has been escalated to your attention.**')
            ->line('**Task Details:**')
            ->line('📋 **Title:** ' . $this->task->title)
            ->line('👤 **Assigned To:** ' . $this->agent->name)
            ->line('🔥 **Priority:** High')
            ->line('⏰ **Was Due:** ' . $this->task->due_date->format('d M Y, h:i A'))
            ->line('⚠️ **Overdue By:** ' . $overdueHours . ' hours')
            ->line('📝 **Description:** ' . $this->task->description)
            ->line('')
            ->line('**Why This Was Escalated:**')
            ->line('• Task is HIGH priority')
            ->line('• Task is overdue by ' . $overdueHours . ' hours')
            ->line('• Automatic escalation triggered')
            ->line('')
            ->action('Review Task', url('/admin/tasks'))
            ->line('Please follow up with ' . $this->agent->name . ' immediately.')
            ->salutation('Best regards, ' . config('app.name'));
    }

    public function toArray($notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'agent_id' => $this->agent->id,
            'agent_name' => $this->agent->name,
            'overdue_hours' => now()->diffInHours($this->task->due_date),
            'message' => "High-priority task escalated: {$this->task->title} (Assigned to {$this->agent->name})",
        ];
    }
}
