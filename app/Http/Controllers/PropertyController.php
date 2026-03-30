<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with('builder')
            ->where('is_active', true)
            ->where('availability_status', 'Available');

        // Filter by property type
        if ($request->filled('type')) {
            $query->where('property_type', $request->type);
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price_min', '>=', $request->min_price * 100000);
        }

        if ($request->filled('max_price')) {
            $query->where('price_max', '<=', $request->max_price * 100000);
        }

        // Filter by bedrooms
        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', $request->bedrooms);
        }

        // Filter by listing type
        if ($request->filled('listing_type')) {
            $query->where('listing_type', $request->listing_type);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        if ($sortBy === 'price') {
            $query->orderBy('price_min', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Featured properties first
        $query->orderBy('is_featured', 'desc');

        $properties = $query->paginate(12);

        return view('properties', compact('properties'));
    }

    public function show($id)
    {
        $property = Property::with(['builder', 'siteVisits'])
            ->where('is_active', true)
            ->findOrFail($id);

        // Increment views count
        $property->increment('views_count');

        // Get similar properties
        $similarProperties = Property::where('is_active', true)
            ->where('id', '!=', $property->id)
            ->where('property_type', $property->property_type)
            ->where('city', $property->city)
            ->limit(3)
            ->get();

        return view('property-detail', compact('property', 'similarProperties'));
    }
}
