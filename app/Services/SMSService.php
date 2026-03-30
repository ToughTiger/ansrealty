<?php

namespace App\Services;

use App\Models\Communication;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected $apiUrl;
    protected $apiKey;
    protected $senderId;

    public function __construct()
    {
        $this->apiUrl = config('services.sms.api_url');
        $this->apiKey = config('services.sms.api_key');
        $this->senderId = config('services.sms.sender_id', 'ANSRLT');
    }

    public function sendSMS(string $to, string $message, $relatedModel = null, $userId = null): ?Communication
    {
        try {
            // Log communication record
            $communication = Communication::create([
                'communication_type' => 'sms',
                'direction' => 'outbound',
                'communicable_type' => $relatedModel ? get_class($relatedModel) : null,
                'communicable_id' => $relatedModel?->id,
                'user_id' => $userId ?? auth()->id(),
                'recipient_type' => 'phone',
                'recipient' => $to,
                'message' => $message,
                'status' => 'pending',
            ]);

            // Send via SMS API (example for Fast2SMS, MSG91, Twilio, etc.)
            $response = Http::asForm()->post($this->apiUrl, [
                'authorization' => $this->apiKey,
                'sender_id' => $this->senderId,
                'message' => $message,
                'numbers' => $to,
                'route' => 'p', // promotional or transactional
            ]);

            if ($response->successful()) {
                $communication->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                    'metadata' => $response->json(),
                ]);

                Log::info("SMS sent to {$to}");
                return $communication;
            } else {
                $communication->update([
                    'status' => 'failed',
                    'metadata' => [
                        'error' => $response->body(),
                        'status_code' => $response->status(),
                    ],
                ]);

                Log::error("SMS send failed to {$to}: " . $response->body());
                return $communication;
            }
        } catch (\Exception $e) {
            Log::error("SMS service error: " . $e->getMessage());
            
            if (isset($communication)) {
                $communication->update([
                    'status' => 'failed',
                    'metadata' => ['error' => $e->getMessage()],
                ]);
            }

            return null;
        }
    }

    public function sendBulkSMS(array $recipients, string $message, $relatedModel = null): array
    {
        $results = [];

        foreach ($recipients as $recipient) {
            $results[] = $this->sendSMS($recipient, $message, $relatedModel);
        }

        return $results;
    }
}
