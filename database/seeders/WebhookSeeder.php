<?php

namespace Database\Seeders;

use App\Models\Webhook;
use Illuminate\Database\Seeder;

class WebhookSeeder extends Seeder
{
    public function run(): void
    {
        $webhooks = [
            [
                'name' => 'Meta (Facebook) Lead Ads',
                'type' => 'meta',
                'endpoint' => url('/api/webhooks/meta-leads'),
                'verify_token' => 'ansrealty_webhook_token',
                'status' => 'active',
                'description' => 'Automatically captures leads from Facebook Lead Ad campaigns. Configure in Facebook Business Manager.',
            ],
            [
                'name' => 'Google Ads Lead Forms',
                'type' => 'google',
                'endpoint' => url('/api/webhooks/google-leads'),
                'status' => 'active',
                'description' => 'Receives leads from Google Ads campaigns. Connect via Zapier or Make.com automation.',
            ],
            [
                'name' => 'Generic Lead API',
                'type' => 'api',
                'endpoint' => url('/api/leads'),
                'status' => 'active',
                'description' => 'General purpose API endpoint for creating leads from any third-party platform (website forms, chatbots, property portals).',
            ],
        ];

        foreach ($webhooks as $webhook) {
            Webhook::create($webhook);
        }
    }
}
