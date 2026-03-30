<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $property->name }} - ANS Realty</title>
    <meta name="description" content="{{ $property->description ?? 'Property details at ' . $property->location }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('home') }}" class="flex items-center">
                    <div class="w-12 h-12 gradient-primary rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-building text-white text-2xl"></i>
                    </div>
                    <span class="text-3xl font-bold text-gray-800">ANS Realty</span>
                </a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-purple-600 font-medium transition">Home</a>
                    <a href="{{ route('properties.index') }}" class="text-gray-700 hover:text-purple-600 font-medium transition">Properties</a>
                    <a href="/about" class="text-gray-700 hover:text-purple-600 font-medium transition">About</a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-purple-600 font-medium transition">Contact</a>
                    <a href="/admin" class="px-6 py-3 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold">Admin Login</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Property Detail -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Breadcrumb -->
            <div class="mb-6 text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-purple-600">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('properties.index') }}" class="hover:text-purple-600">Properties</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900">{{ $property->name }}</span>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Image Gallery -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
                        <div class="relative h-96 bg-gray-200">
                            <img src="{{ $property->first_image }}" alt="{{ $property->name }}" class="w-full h-full object-cover">
                            @if($property->is_featured)
                                <span class="absolute top-4 left-4 bg-yellow-500 text-white px-4 py-2 rounded-full font-semibold">
                                    <i class="fas fa-star mr-1"></i>Featured
                                </span>
                            @endif
                            @if($property->is_hot)
                                <span class="absolute top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-full font-semibold">
                                    <i class="fas fa-fire mr-1"></i>Hot Deal
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Property Overview -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ $property->name }}</h1>
                                <p class="text-xl text-gray-600">
                                    <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                                    {{ $property->full_address }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mb-6">
                            <span class="px-4 py-2 bg-purple-100 text-purple-700 rounded-full font-semibold">
                                {{ $property->property_type }}
                            </span>
                            <span class="px-4 py-2 bg-green-100 text-green-700 rounded-full font-semibold">
                                For {{ $property->listing_type }}
                            </span>
                            @if($property->possession_status)
                                <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full font-semibold">
                                    {{ $property->possession_status }}
                                </span>
                            @endif
                        </div>

                        <div class="border-t border-b py-6 mb-6">
                            <h2 class="text-3xl font-bold gradient-primary bg-clip-text text-transparent mb-2">
                                {{ $property->price_range ?? 'Price on Request' }}
                            </h2>
                            @if($property->builder)
                                <p class="text-gray-600">
                                    <i class="fas fa-building text-purple-600 mr-2"></i>
                                    By {{ $property->builder->name }}
                                </p>
                            @endif
                        </div>

                        <!-- Key Features -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                            @if($property->bedrooms)
                                <div class="text-center p-4 bg-gray-50 rounded-lg">
                                    <i class="fas fa-bed text-purple-600 text-3xl mb-2"></i>
                                    <p class="font-semibold text-gray-800">{{ $property->bedrooms }} Bedrooms</p>
                                </div>
                            @endif
                            @if($property->bathrooms)
                                <div class="text-center p-4 bg-gray-50 rounded-lg">
                                    <i class="fas fa-bath text-purple-600 text-3xl mb-2"></i>
                                    <p class="font-semibold text-gray-800">{{ $property->bathrooms }} Bathrooms</p>
                                </div>
                            @endif
                            @if($property->carpet_area)
                                <div class="text-center p-4 bg-gray-50 rounded-lg">
                                    <i class="fas fa-ruler-combined text-purple-600 text-3xl mb-2"></i>
                                    <p class="font-semibold text-gray-800">{{ $property->carpet_area }} {{ $property->area_unit }}</p>
                                </div>
                            @endif
                            @if($property->parking)
                                <div class="text-center p-4 bg-gray-50 rounded-lg">
                                    <i class="fas fa-car text-purple-600 text-3xl mb-2"></i>
                                    <p class="font-semibold text-gray-800">{{ $property->parking }} Parking</p>
                                </div>
                            @endif
                        </div>

                        <!-- Description -->
                        @if($property->description)
                            <div class="mb-8">
                                <h3 class="text-2xl font-bold text-gray-800 mb-4">About This Property</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $property->description }}</p>
                            </div>
                        @endif

                        <!-- Amenities -->
                        @if($property->amenities && count($property->amenities) > 0)
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-4">Amenities</h3>
                                <div class="grid md:grid-cols-3 gap-4">
                                    @foreach($property->amenities as $amenity)
                                        <div class="flex items-center text-gray-700">
                                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                            <span>{{ $amenity }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Property Details -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Property Details</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            @if($property->property_type)
                                <div class="flex justify-between py-3 border-b">
                                    <span class="text-gray-600">Property Type</span>
                                    <span class="font-semibold">{{ $property->property_type }}</span>
                                </div>
                            @endif
                            @if($property->listing_type)
                                <div class="flex justify-between py-3 border-b">
                                    <span class="text-gray-600">Listing Type</span>
                                    <span class="font-semibold">For {{ $property->listing_type }}</span>
                                </div>
                            @endif
                            @if($property->carpet_area)
                                <div class="flex justify-between py-3 border-b">
                                    <span class="text-gray-600">Carpet Area</span>
                                    <span class="font-semibold">{{ $property->carpet_area }} {{ $property->area_unit }}</span>
                                </div>
                            @endif
                            @if($property->built_up_area)
                                <div class="flex justify-between py-3 border-b">
                                    <span class="text-gray-600">Built-up Area</span>
                                    <span class="font-semibold">{{ $property->built_up_area }} {{ $property->area_unit }}</span>
                                </div>
                            @endif
                            @if($property->floor_number)
                                <div class="flex justify-between py-3 border-b">
                                    <span class="text-gray-600">Floor</span>
                                    <span class="font-semibold">{{ $property->floor_number }} of {{ $property->total_floors }}</span>
                                </div>
                            @endif
                            @if($property->possession_date)
                                <div class="flex justify-between py-3 border-b">
                                    <span class="text-gray-600">Possession</span>
                                    <span class="font-semibold">{{ $property->possession_date->format('M Y') }}</span>
                                </div>
                            @endif
                            @if($property->rera_number)
                                <div class="flex justify-between py-3 border-b">
                                    <span class="text-gray-600">RERA Number</span>
                                    <span class="font-semibold">{{ $property->rera_number }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Inquiry Form -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24 mb-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Interested in this property?</h3>
                        
                        @if(session('success'))
                            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded text-sm">
                                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('inquiries.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="property_id" value="{{ $property->id }}">
                            <input type="hidden" name="inquiry_type" value="Property">

                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2 text-sm">Full Name *</label>
                                <input type="text" name="full_name" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600"
                                       placeholder="Enter your name">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2 text-sm">Mobile Number *</label>
                                <input type="tel" name="mobile" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600"
                                       placeholder="Enter your mobile">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2 text-sm">Email</label>
                                <input type="email" name="email"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600"
                                       placeholder="Enter your email">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2 text-sm">Message</label>
                                <textarea name="message" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600"
                                          placeholder="Your requirements..."></textarea>
                            </div>

                            <button type="submit" class="w-full gradient-primary text-white py-3 rounded-lg font-semibold hover:opacity-90 transition">
                                <i class="fas fa-paper-plane mr-2"></i>Send Inquiry
                            </button>
                        </form>

                        <div class="mt-6 space-y-3">
                            <a href="https://wa.me/919876543210?text=I'm interested in {{ $property->name }}" target="_blank"
                               class="block text-center w-full py-3 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition">
                                <i class="fab fa-whatsapp mr-2"></i>WhatsApp Us
                            </a>
                            <a href="tel:+919876543210"
                               class="block text-center w-full py-3 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 transition">
                                <i class="fas fa-phone mr-2"></i>Call Now
                            </a>
                        </div>
                    </div>

                    <!-- Share -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Share This Property</h3>
                        <div class="flex gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank"
                               class="flex-1 py-3 bg-blue-600 text-white rounded-lg text-center hover:bg-blue-700 transition">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}" target="_blank"
                               class="flex-1 py-3 bg-sky-500 text-white rounded-lg text-center hover:bg-sky-600 transition">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($property->name . ' - ' . url()->current()) }}" target="_blank"
                               class="flex-1 py-3 bg-green-500 text-white rounded-lg text-center hover:bg-green-600 transition">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Similar Properties -->
            @if($similarProperties->count() > 0)
                <div class="mt-16">
                    <h2 class="text-3xl font-bold text-gray-800 mb-8">Similar Properties</h2>
                    <div class="grid md:grid-cols-3 gap-8">
                        @foreach($similarProperties as $similar)
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition">
                                <img src="{{ $similar->first_image }}" alt="{{ $similar->name }}" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $similar->name }}</h3>
                                    <p class="text-gray-600 mb-4">
                                        <i class="fas fa-map-marker-alt text-purple-600 mr-1"></i>
                                        {{ $similar->location }}
                                    </p>
                                    <p class="text-2xl font-bold gradient-primary bg-clip-text text-transparent mb-4">
                                        {{ $similar->price_range }}
                                    </p>
                                    <a href="{{ route('properties.show', $similar->id) }}"
                                       class="block text-center w-full py-3 gradient-primary text-white rounded-lg font-semibold hover:opacity-90 transition">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/919876543210?text=I'm interested in {{ $property->name }}" target="_blank"
       class="fixed bottom-8 right-8 w-16 h-16 bg-green-500 rounded-full flex items-center justify-center shadow-2xl hover:bg-green-600 transition z-50">
        <i class="fab fa-whatsapp text-white text-3xl"></i>
    </a>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4">ANS Realty</h3>
                    <p class="text-gray-400">Your trusted partner in finding the perfect property.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                        <li><a href="{{ route('properties.index') }}" class="hover:text-white">Properties</a></li>
                        <li><a href="/about" class="hover:text-white">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Property Types</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('properties.index', ['type' => 'Flat']) }}" class="hover:text-white">Apartments</a></li>
                        <li><a href="{{ route('properties.index', ['type' => 'Villa']) }}" class="hover:text-white">Villas</a></li>
                        <li><a href="{{ route('properties.index', ['type' => 'Plot']) }}" class="hover:text-white">Plots</a></li>
                        <li><a href="{{ route('properties.index', ['type' => 'Commercial']) }}" class="hover:text-white">Commercial</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Connect With Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2026 ANS Realty. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
