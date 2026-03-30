<?php

namespace App\Imports;

use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\Log;

class LeadsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    public function model(array $row)
    {
        // Find or create lead source
        $leadSource = LeadSource::firstOrCreate(
            ['name' => $row['lead_source'] ?? 'Imported'],
            ['slug' => \Illuminate\Support\Str::slug($row['lead_source'] ?? 'imported'), 'color' => '#6b7280']
        );

        // Find lead status (default to "New")
        $leadStatus = LeadStatus::where('name', $row['lead_status'] ?? 'New')->first() 
                    ?? LeadStatus::first();

        // Find assigned agent by email or name
        $assignedTo = null;
        if (!empty($row['assigned_agent_email'])) {
            $agent = User::where('email', $row['assigned_agent_email'])->first();
            $assignedTo = $agent?->id;
        }

        // Parse property types
        $propertyTypes = null;
        if (!empty($row['property_types'])) {
            $types = explode(',', $row['property_types']);
            $propertyTypes = array_map('trim', $types);
        }

        // Parse preferred locations
        $preferredLocations = null;
        if (!empty($row['preferred_locations'])) {
            $locations = explode(',', $row['preferred_locations']);
            $preferredLocations = array_map('trim', $locations);
        }

        return new Lead([
            'full_name' => $row['full_name'],
            'mobile' => $row['mobile'],
            'email' => $row['email'] ?? null,
            'alternate_mobile' => $row['alternate_mobile'] ?? null,
            'budget_min' => $row['budget_min'] ?? null,
            'budget_max' => $row['budget_max'] ?? null,
            'preferred_locations' => $preferredLocations,
            'property_types' => $propertyTypes,
            'purchase_intent' => $row['purchase_intent'] ?? 'Buy',
            'priority' => $row['priority'] ?? 'Warm',
            'lead_source_id' => $leadSource->id,
            'lead_status_id' => $leadStatus->id,
            'assigned_to' => $assignedTo,
            'notes' => $row['notes'] ?? null,
            'remarks' => $row['remarks'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'purchase_intent' => 'nullable|in:Buy,Rent,Invest',
            'priority' => 'nullable|in:Hot,Warm,Cold',
        ];
    }
}
