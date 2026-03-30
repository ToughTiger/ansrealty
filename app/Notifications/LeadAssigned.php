<?php

namespace App\Notifications;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadAssigned extends Notification
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
        $budgetRange = $this->lead->budget_min && $this->lead->budget_max 
            ? '₹' . number_format($this->lead->budget_min) . ' - ₹' . number_format($this->lead->budget_max)
            : 'Not specified';

        return (new MailMessage)
            ->subject('🎯 New Lead Assigned: ' . $this->lead->full_name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new lead has been assigned to you.')
            ->line('**Customer Details:**')
            ->line('📱 **Name:** ' . $this->lead->full_name)
            ->line('📞 **Mobile:** ' . $this->lead->mobile)
            ->line('📧 **Email:** ' . ($this->lead->email ?: 'Not provided'))
            ->line('💰 **Budget:** ' . $budgetRange)
            ->line('🔥 **Priority:** ' . $this->lead->priority)
            ->line('📍 **Locations:** ' . $this->getLocations())
            ->line('🏠 **Property Types:** ' . $this->getPropertyTypes())
            ->action('View Lead', url('/admin/leads/' . $this->lead->id . '/edit'))
            ->line('⏰ **Action Required:** Please contact the customer within 1 hour.')
            ->line('A task has been automatically created for you.')
            ->salutation('Best regards, ' . config('app.name'));
    }

    public function toArray($notifiable): array
    {
        return [
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->full_name,
            'lead_mobile' => $this->lead->mobile,
            'priority' => $this->lead->priority,
            'message' => "New {$this->lead->priority} priority lead assigned: {$this->lead->full_name}",
        ];
    }

    protected function getLocations(): string
    {
        if (!$this->lead->preferred_locations) return 'Not specified';
        
        $locations = is_array($this->lead->preferred_locations) 
            ? $this->lead->preferred_locations 
            : json_decode($this->lead->preferred_locations, true);
        
        return is_array($locations) ? implode(', ', $locations) : 'Not specified';
    }

    protected function getPropertyTypes(): string
    {
        if (!$this->lead->property_types) return 'Not specified';
        
        $types = is_array($this->lead->property_types) 
            ? $this->lead->property_types 
            : json_decode($this->lead->property_types, true);
        
        return is_array($types) ? implode(', ', $types) : 'Not specified';
    }
}
