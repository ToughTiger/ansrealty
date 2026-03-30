<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class BookingObserver
{
    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        // Check if stage changed
        if ($booking->isDirty('stage')) {
            try {
                $taskConfig = $this->getTaskForStage($booking->stage);
                
                if ($taskConfig) {
                    Task::create([
                        'taskable_type' => Booking::class,
                        'taskable_id' => $booking->id,
                        'assigned_to' => $booking->opportunity?->assigned_to,
                        'type' => $taskConfig['type'],
                        'title' => $taskConfig['title'],
                        'description' => $taskConfig['description'],
                        'due_date' => now()->add($taskConfig['due_in']),
                        'priority' => $taskConfig['priority'],
                        'status' => 'Pending',
                    ]);

                    Log::info("Auto-task created for Booking #{$booking->id} stage: {$booking->stage}");
                }
            } catch (\Exception $e) {
                Log::error("Booking task creation failed: " . $e->getMessage());
            }
        }

        // Check if token payment received
        if ($booking->isDirty('token_amount') && $booking->token_amount > 0) {
            try {
                Task::create([
                    'taskable_type' => Booking::class,
                    'taskable_id' => $booking->id,
                    'assigned_to' => $booking->opportunity?->assigned_to,
                    'type' => 'Administrative',
                    'title' => 'Send Token Receipt',
                    'description' => "Token amount ₹" . number_format($booking->token_amount) . " received. Send official receipt to customer.",
                    'due_date' => now()->addHours(2),
                    'priority' => 'High',
                    'status' => 'Pending',
                ]);

                Log::info("Token receipt task created for Booking #{$booking->id}");
            } catch (\Exception $e) {
                Log::error("Token receipt task creation failed: " . $e->getMessage());
            }
        }
    }

    /**
     * Get task configuration based on booking stage
     */
    protected function getTaskForStage(string $stage): ?array
    {
        $tasks = [
            'Token Received' => [
                'type' => 'Administrative',
                'title' => 'Confirm Token & Process',
                'description' => 'Verify token payment. Update booking status. Inform customer of next steps.',
                'due_in' => '4 hours',
                'priority' => 'High',
            ],
            'Token Confirmed' => [
                'type' => 'Administrative',
                'title' => 'Prepare Agreement Documents',
                'description' => 'Prepare sale agreement, payment schedule, terms & conditions.',
                'due_in' => '2 days',
                'priority' => 'High',
            ],
            'Agreement Pending' => [
                'type' => 'Meeting',
                'title' => 'Schedule Agreement Signing',
                'description' => 'Coordinate with customer to schedule agreement signing meeting.',
                'due_in' => '3 days',
                'priority' => 'High',
            ],
            'Agreement Signed' => [
                'type' => 'Administrative',
                'title' => 'Process Payment Plan',
                'description' => 'Set up payment plan. Send payment schedule to customer. Configure auto-reminders.',
                'due_in' => '1 day',
                'priority' => 'Medium',
            ],
            'Payment Plan Active' => [
                'type' => 'Email',
                'title' => 'Payment Reminder',
                'description' => 'Send payment reminder for upcoming installment as per schedule.',
                'due_in' => '7 days',
                'priority' => 'Medium',
            ],
            'Registration Pending' => [
                'type' => 'Administrative',
                'title' => 'Coordinate Registration',
                'description' => 'Collect documents. Schedule registration appointment with sub-registrar.',
                'due_in' => '5 days',
                'priority' => 'High',
            ],
            'Registration Done' => [
                'type' => 'Administrative',
                'title' => 'Possession Preparation',
                'description' => 'Coordinate with builder for possession. Verify completion status.',
                'due_in' => '7 days',
                'priority' => 'Medium',
            ],
            'Possession Pending' => [
                'type' => 'Call',
                'title' => 'Possession Coordination',
                'description' => 'Confirm possession date with customer and builder. Arrange handover.',
                'due_in' => '3 days',
                'priority' => 'High',
            ],
            'Possession Done' => [
                'type' => 'Call',
                'title' => 'Post-Possession Follow-up',
                'description' => 'Follow-up with customer. Ensure satisfaction. Request review/referral.',
                'due_in' => '7 days',
                'priority' => 'Low',
            ],
        ];

        return $tasks[$stage] ?? null;
    }
}
