<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaleLeadAlert extends Notification
{
    use Queueable;

    protected $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable): MailMessage
    {
        $daysSinceActivity = $this->lead->last_activity_at 
            ? now()->diffInDays($this->lead->last_activity_at)
            : 'Never';

        $reEngageUrl = url('/admin/leads/' . $this->lead->id . '/edit');

        return (new MailMessage)
            ->subject('🚨 URGENT: Stale Lead Alert - ' . $this->lead->full_name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('⚠️ **A lead assigned to you has become stale and needs immediate attention!**')
            ->line('**Lead Details:**')
            ->line('👤 **Name:** ' . $this->lead->full_name)
            ->line('📞 **Mobile:** ' . $this->lead->mobile)
            ->line('🔥 **Priority:** ' . $this->lead->priority)
            ->line('📅 **Last Activity:** ' . ($this->lead->last_activity_at ? $this->lead->last_activity_at->format('d M Y') : 'Never'))
            ->line('⏰ **Days Inactive:** ' . $daysSinceActivity)
            ->line('💰 **Budget:** ₹' . number_format($this->lead->budget_min ?? 0) . ' - ₹' . number_format($this->lead->budget_max ?? 0))
            ->line('')
            ->line('**Why This Matters:**')
            ->line('• Lead is going cold and may be lost to competitors')
            ->line('• Every day of inactivity reduces conversion probability by 5-10%')
            ->line('• Customer may have already moved on')
            ->line('')
            ->line('**Recommended Actions:**')
            ->line('1. 📞 Call the customer immediately')
            ->line('2. 📧 Send property options via WhatsApp/Email')
            ->line('3. 📅 Schedule a site visit this week')
            ->line('4. 💬 Update lead status and notes')
            ->line('')
            ->action('🔄 Re-Engage Lead Now', $reEngageUrl)
            ->line('⏰ **Urgent:** Please take action within 24 hours to avoid losing this lead.')
            ->salutation('Don\'t let this opportunity slip away! - ' . config('app.name'));
    }

    public function toArray($notifiable): array
    {
        return [
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->full_name,
            'lead_mobile' => $this->lead->mobile,
            'priority' => $this->lead->priority,
            'days_inactive' => $this->lead->last_activity_at 
                ? now()->diffInDays($this->lead->last_activity_at)
                : null,
            'message' => "🚨 Stale lead alert: {$this->lead->full_name} - No activity for 14+ days",
        ];
    }
}
