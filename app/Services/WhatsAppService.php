<?php

namespace App\Services;

use App\Models\Communication;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $apiKey;
    protected $fromNumber;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url');
        $this->apiKey = config('services.whatsapp.api_key');
        $this->fromNumber = config('services.whatsapp.from_number');
    }

    public function sendMessage(string $to, string $message, $relatedModel = null, $userId = null): ?Communication
    {
        try {
            // Log communication record first
            $communication = Communication::create([
                'communication_type' => 'whatsapp',
                'direction' => 'outbound',
                'communicable_type' => $relatedModel ? get_class($relatedModel) : null,
                'communicable_id' => $relatedModel?->id,
                'user_id' => $userId ?? auth()->id(),
                'recipient_type' => 'phone',
                'recipient' => $to,
                'message' => $message,
                'status' => 'pending',
            ]);

            // Send via WhatsApp API (using a generic example)
            // Replace with actual provider: Twilio, WhatsApp Business API, etc.
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/messages', [
                'from' => $this->fromNumber,
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'body' => $message,
                ],
            ]);

            if ($response->successful()) {
                $communication->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                    'metadata' => $response->json(),
                ]);

                Log::info("WhatsApp message sent to {$to}");
                return $communication;
            } else {
                $communication->update([
                    'status' => 'failed',
                    'metadata' => [
                        'error' => $response->body(),
                        'status_code' => $response->status(),
                    ],
                ]);

                Log::error("WhatsApp send failed to {$to}: " . $response->body());
                return $communication;
            }
        } catch (\Exception $e) {
            Log::error("WhatsApp service error: " . $e->getMessage());
            
            if (isset($communication)) {
                $communication->update([
                    'status' => 'failed',
                    'metadata' => ['error' => $e->getMessage()],
                ]);
            }

            return null;
        }
    }

    public function sendTemplateMessage(string $to, string $templateName, array $variables = [], $relatedModel = null): ?Communication
    {
        // Implementation for template-based WhatsApp messages
        // This is typically used with WhatsApp Business API approved templates
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/messages', [
                'from' => $this->fromNumber,
                'to' => $to,
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => [
                        'code' => 'en',
                    ],
                    'components' => [
                        [
                            'type' => 'body',
                            'parameters' => array_map(fn($v) => ['type' => 'text', 'text' => $v], $variables),
                        ],
                    ],
                ],
            ]);

            $communication = Communication::create([
                'communication_type' => 'whatsapp',
                'direction' => 'outbound',
                'communicable_type' => $relatedModel ? get_class($relatedModel) : null,
                'communicable_id' => $relatedModel?->id,
                'user_id' => auth()->id(),
                'recipient_type' => 'phone',
                'recipient' => $to,
                'message' => "Template: {$templateName}",
                'status' => $response->successful() ? 'sent' : 'failed',
                'sent_at' => $response->successful() ? now() : null,
                'metadata' => $response->json(),
            ]);

            return $communication;
        } catch (\Exception $e) {
            Log::error("WhatsApp template send error: " . $e->getMessage());
            return null;
        }
    }
}
