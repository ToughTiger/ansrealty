<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'message' => 'nullable|string|max:1000',
            'property_id' => 'nullable|exists:properties,id',
            'inquiry_type' => 'nullable|string|in:General,Property,Site Visit,Callback',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create inquiry record
        $inquiry = Inquiry::create([
            'full_name' => $request->full_name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'message' => $request->message,
            'property_id' => $request->property_id,
            'inquiry_type' => $request->inquiry_type ?? 'General',
            'source' => 'Website',
            'status' => 'New',
        ]);

        // Check if lead already exists
        $existingLead = Lead::where('mobile', $request->mobile)->first();

        if (!$existingLead) {
            // Get default lead source and status
            $websiteSource = LeadSource::where('slug', 'website')->first();
            $newStatus = LeadStatus::where('slug', 'new')->first();

            // Create new lead
            Lead::create([
                'full_name' => $request->full_name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'lead_source_id' => $websiteSource?->id,
                'lead_status_id' => $newStatus?->id,
                'priority' => 'Warm',
                'notes' => $request->message,
                'purchase_intent' => 'Buy',
                'first_contact_at' => now(),
            ]);
        }

        // TODO: Send email notification to admin
        // TODO: Send SMS/WhatsApp confirmation to customer

        return back()->with('success', 'Thank you for your inquiry! Our team will contact you shortly.');
    }

    public function contact()
    {
        return view('contact');
    }
}
