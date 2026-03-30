<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'meta' => [
        'webhook_verify_token' => env('META_WEBHOOK_VERIFY_TOKEN', 'ansrealty_webhook_token'),
    ],

    'whatsapp' => [
        'api_url' => env('WHATSAPP_API_URL', 'https://api.whatsapp.com/v1'),
        'api_key' => env('WHATSAPP_API_KEY'),
        'from_number' => env('WHATSAPP_FROM_NUMBER'),
    ],

    'sms' => [
        'api_url' => env('SMS_API_URL', 'https://www.fast2sms.com/dev/bulkV2'),
        'api_key' => env('SMS_API_KEY'),
        'sender_id' => env('SMS_SENDER_ID', 'ANSRLT'),
    ],

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from' => env('TWILIO_FROM_NUMBER'),
    ],

];
