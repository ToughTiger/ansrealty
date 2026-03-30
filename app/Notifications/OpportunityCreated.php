<?php

namespace App\Notifications;

use App\Models\Opportunity;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OpportunityCreated extends Notification
{
    use Queueable;

    protected $opportunity;

    public function __construct(Opportunity $opportunity)
    {
        $this->opportunity = $opportunity;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable): MailMessage
    {
        $lead = $this->opportunity->lead;
        
        return (new MailMessage)
            ->subject('🎯 New Opportunity Created!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! A new opportunity has been created and assigned to you.')
            ->line('**Opportunity Details:**')
            ->line('👤 **Customer:** ' . $lead->full_name)
            ->line('📞 **Mobile:** ' . $lead->mobile)
            ->line('💰 **Deal Value:** ₹' . number_format($this->opportunity->deal_value ?? 0))
            ->line('📊 **Stage:** ' . $this->opportunity->opportunityStage->name)
            ->line('🎲 **Win Probability:** ' . $this->opportunity->opportunityStage->probability . '%')
            ->line('📅 **Expected Close:** ' . ($this->opportunity->expected_close_date ? $this->opportunity->expected_close_date->format('d M Y') : 'Not set'))
            ->action('View Opportunity', url('/admin/opportunities/' . $this->opportunity->id . '/edit'))
            ->line('🚀 Let\'s close this deal!')
            ->salutation('Best regards, ' . config('app.name'));
    }

    public function toArray($notifiable): array
    {
        return [
            'opportunity_id' => $this->opportunity->id,
            'customer_name' => $this->opportunity->lead->full_name,
            'deal_value' => $this->opportunity->deal_value,
            'stage' => $this->opportunity->opportunityStage->name,
            'message' => "New opportunity created: {$this->opportunity->lead->full_name}",
        ];
    }
}
