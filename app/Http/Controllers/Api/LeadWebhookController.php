<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LeadWebhookController extends Controller
{
    /**
     * Handle Meta (Facebook) Lead Ads webhook
     * POST /api/webhooks/meta-leads
     */
    public function metaLeads(Request $request)
    {
        Log::info('Meta Lead Webhook Received', $request->all());

        try {
            // Meta sends data in specific format
            $entry = $request->input('entry.0.changes.0.value', []);
            
            if (empty($entry)) {
                return response()->json(['status' => 'no_data'], 200);
            }

            $leadData = $entry['leadgen_id'] ?? $entry;
            
            // Extract lead information
            $fullName = $leadData['field_data'][0]['values'][0] ?? 'Unknown';
            $mobile = null;
            $email = null;
            
            foreach ($leadData['field_data'] ?? [] as $field) {
                if (in_array($field['name'], ['phone_number', 'mobile', 'phone'])) {
                    $mobile = $field['values'][0];
                }
                if ($field['name'] === 'email') {
                    $email = $field['values'][0];
                }
            }

            // Get or create Facebook Ads lead source
            $leadSource = LeadSource::firstOrCreate(
                ['name' => 'Facebook Ads'],
                ['slug' => 'facebook-ads', 'color' => '#1877f2']
            );

            // Get "New" status
            $leadStatus = LeadStatus::where('name', 'New')->first() 
                        ?? LeadStatus::first();

            // Create lead
            $lead = Lead::create([
                'full_name' => $fullName,
                'mobile' => $mobile ?? 'Not provided',
                'email' => $email,
                'lead_source_id' => $leadSource->id,
                'lead_status_id' => $leadStatus->id,
                'priority' => 'Hot',
                'purchase_intent' => 'Buy',
                'notes' => 'Auto-imported from Facebook Lead Ad',
            ]);

            Log::info('Meta Lead Created', ['lead_id' => $lead->id]);

            return response()->json([
                'status' => 'success',
                'lead_id' => $lead->id,
                'message' => 'Lead created successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Meta Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle Meta webhook verification (GET request)
     */
    public function metaVerify(Request $request)
    {
        $mode = $request->input('hub_mode');
        $token = $request->input('hub_verify_token');
        $challenge = $request->input('hub_challenge');

        $verifyToken = config('services.meta.webhook_verify_token', 'ansrealty_webhook_token');

        if ($mode === 'subscribe' && $token === $verifyToken) {
            Log::info('Meta Webhook Verified');
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        return response()->json(['status' => 'error'], 403);
    }

    /**
     * Handle Google Ads webhook
     * POST /api/webhooks/google-leads
     */
    public function googleLeads(Request $request)
    {
        Log::info('Google Lead Webhook Received', $request->all());

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Get or create Google Ads lead source
            $leadSource = LeadSource::firstOrCreate(
                ['name' => 'Google Ads'],
                ['slug' => 'google-ads', 'color' => '#4285f4']
            );

            $leadStatus = LeadStatus::where('name', 'New')->first() 
                        ?? LeadStatus::first();

            $lead = Lead::create([
                'full_name' => $request->input('name'),
                'mobile' => $request->input('phone'),
                'email' => $request->input('email'),
                'lead_source_id' => $leadSource->id,
                'lead_status_id' => $leadStatus->id,
                'priority' => 'Hot',
                'purchase_intent' => 'Buy',
                'notes' => 'Auto-imported from Google Ads',
            ]);

            Log::info('Google Lead Created', ['lead_id' => $lead->id]);

            return response()->json([
                'status' => 'success',
                'lead_id' => $lead->id,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Google Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Generic Lead API endpoint
     * POST /api/leads
     */
    public function createLead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'nullable|email',
            'lead_source' => 'nullable|string',
            'purchase_intent' => 'nullable|in:Buy,Rent,Invest',
            'priority' => 'nullable|in:Hot,Warm,Cold',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Get or create lead source
            $leadSourceName = $request->input('lead_source', 'API');
            $leadSource = LeadSource::firstOrCreate(
                ['name' => $leadSourceName],
                ['slug' => \Illuminate\Support\Str::slug($leadSourceName), 'color' => '#6b7280']
            );

            $leadStatus = LeadStatus::where('name', 'New')->first() 
                        ?? LeadStatus::first();

            $lead = Lead::create([
                'full_name' => $request->input('full_name'),
                'mobile' => $request->input('mobile'),
                'email' => $request->input('email'),
                'budget_min' => $request->input('budget_min'),
                'budget_max' => $request->input('budget_max'),
                'preferred_locations' => $request->input('preferred_locations'),
                'property_types' => $request->input('property_types'),
                'purchase_intent' => $request->input('purchase_intent', 'Buy'),
                'priority' => $request->input('priority', 'Warm'),
                'lead_source_id' => $leadSource->id,
                'lead_status_id' => $leadStatus->id,
                'notes' => $request->input('notes'),
            ]);

            return response()->json([
                'status' => 'success',
                'lead_id' => $lead->id,
                'message' => 'Lead created successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error('API Lead Creation Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
