<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LeadWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Meta (Facebook) Lead Ads Webhook
Route::get('/webhooks/meta-leads', [LeadWebhookController::class, 'metaVerify']);
Route::post('/webhooks/meta-leads', [LeadWebhookController::class, 'metaLeads']);

// Google Ads Webhook
Route::post('/webhooks/google-leads', [LeadWebhookController::class, 'googleLeads']);

// Generic Lead Creation API
Route::post('/leads', [LeadWebhookController::class, 'createLead']);
