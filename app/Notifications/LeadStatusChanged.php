<?php

namespace App\Notifications;

use App\Models\Lead;
use App\Models\LeadStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadStatusChanged extends Notification
{
    use Queueable;

    protected $lead;
    protected $oldStatus;
    protected $newStatus;

    public function __construct(Lead $lead, ?LeadStatus $oldStatus, LeadStatus $newStatus)
    {
        $this->lead = $lead;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable): MailMessage
    {
        $oldStatusName = $this->oldStatus ? $this->oldStatus->name : 'None';
        
        return (new MailMessage)
            ->subject('📊 Lead Status Updated: ' . $this->lead->full_name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A lead status has been updated.')
            ->line('**Lead:** ' . $this->lead->full_name)
            ->line('**Mobile:** ' . $this->lead->mobile)
            ->line('**Status Change:**')
            ->line('📍 From: **' . $oldStatusName . '**')
            ->line('📍 To: **' . $this->newStatus->name . '**')
            ->line('**Priority:** ' . $this->lead->priority)
            ->action('View Lead', url('/admin/leads/' . $this->lead->id . '/edit'))
            ->line($this->getNextStepMessage())
            ->salutation('Best regards, ' . config('app.name'));
    }

    public function toArray($notifiable): array
    {
        return [
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->full_name,
            'old_status' => $this->oldStatus ? $this->oldStatus->name : null,
            'new_status' => $this->newStatus->name,
            'message' => "Lead status changed to {$this->newStatus->name}: {$this->lead->full_name}",
        ];
    }

    protected function getNextStepMessage(): string
    {
        $messages = [
            'New' => '⏰ Action Required: Make initial contact within 1 hour.',
            'Contacted' => '💬 Next: Qualify the lead and understand requirements.',
            'Qualified' => '📅 Next: Schedule a site visit or send property details.',
            'Site Visit Planned' => '🏠 Next: Confirm site visit details with customer.',
            'Site Visit Done' => '📞 Next: Follow-up to gather feedback.',
            'Negotiation' => '💰 Next: Work on pricing and terms.',
            'Converted' => '🎉 Congratulations! Lead converted to opportunity.',
            'Lost' => '📝 Please update the lost reason for analysis.',
        ];

        return $messages[$this->newStatus->name] ?? 'Continue following up with the lead.';
    }
}
