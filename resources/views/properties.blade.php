<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties - Find Your Dream Home | ANS Realty</title>
    <meta name="description" content="Browse through our wide selection of properties in Mumbai. Find apartments, villas, plots, and commercial spaces.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
        .property-card img { transition: transform 0.5s ease; }
        .property-card:hover img { transform: scale(1.1); }
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
                    <a href="{{ route('properties.index') }}" class="text-purple-600 font-bold transition">Properties</a>
                    <a href="/about" class="text-gray-700 hover:text-purple-600 font-medium transition">About</a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-purple-600 font-medium transition">Contact</a>
                    <a href="/admin" class="px-6 py-3 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold">Admin Login</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="bg-gradient-to-r from-purple-600 to-blue-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-5xl font-bold mb-4">Explore Properties</h1>
            <p class="text-xl text-gray-200">{{ $properties->total() }} properties found</p>
        </div>
    </section>

    <!-- Search and Filters -->
    <section class="py-8 bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4">
            <form method="GET" action="{{ route('properties.index') }}" class="bg-white rounded-xl p-6 border-2 border-gray-200">
                <div class="grid md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-1"></i>Location
                        </label>
                        <input type="text" name="location" value="{{ request('location') }}" 
                               placeholder="Enter area or locality"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-building text-purple-600 mr-1"></i>Property Type
                        </label>
                        <select name="type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600">
                            <option value="">All Types</option>
                            <option value="Flat" {{ request('type') == 'Flat' ? 'selected' : '' }}>Apartment</option>
                            <option value="Villa" {{ request('type') == 'Villa' ? 'selected' : '' }}>Villa</option>
                            <option value="Plot" {{ request('type') == 'Plot' ? 'selected' : '' }}>Plot</option>
                            <option value="Commercial" {{ request('type') == 'Commercial' ? 'selected' : '' }}>Commercial</option>
                            <option value="Penthouse" {{ request('type') == 'Penthouse' ? 'selected' : '' }}>Penthouse</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-bed text-purple-600 mr-1"></i>Bedrooms
                        </label>
                        <select name="bedrooms" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600">
                            <option value="">Any</option>
                            <option value="1" {{ request('bedrooms') == '1' ? 'selected' : '' }}>1 BHK</option>
                            <option value="2" {{ request('bedrooms') == '2' ? 'selected' : '' }}>2 BHK</option>
                            <option value="3" {{ request('bedrooms') == '3' ? 'selected' : '' }}>3 BHK</option>
                            <option value="4" {{ request('bedrooms') == '4' ? 'selected' : '' }}>4+ BHK</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-rupee-sign text-purple-600 mr-1"></i>Min Price (Lakhs)
                        </label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" 
                               placeholder="Min" min="0" step="5"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-rupee-sign text-purple-600 mr-1"></i>Max Price (Lakhs)
                        </label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" 
                               placeholder="Max" min="0" step="5"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600">
                    </div>
                </div>

                <div class="flex gap-4 mt-4">
                    <button type="submit" class="px-8 py-3 gradient-primary text-white rounded-lg font-semibold hover:opacity-90 transition">
                        <i class="fas fa-search mr-2"></i>Search Properties
                    </button>
                    <a href="{{ route('properties.index') }}" class="px-8 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">
                        <i class="fas fa-redo mr-2"></i>Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </section>

    <!-- Properties Grid -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4">
            @if($properties->count() > 0)
                <div class="grid md:grid-cols-3 gap-8">
                    @foreach($properties as $property)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift property-card">
                            <div class="relative h-64 overflow-hidden">
                                <img src="{{ $property->first_image }}" 
                                     alt="{{ $property->name }}" 
                                     class="w-full h-full object-cover">
                                @if($property->is_featured)
                                    <span class="absolute top-4 left-4 bg-yellow-500 text-white px-4 py-2 rounded-full font-semibold text-sm">
                                        <i class="fas fa-star mr-1"></i>Featured
                                    </span>
                                @endif
                                @if($property->is_hot)
                                    <span class="absolute top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-full font-semibold text-sm">
                                        <i class="fas fa-fire mr-1"></i>Hot
                                    </span>
                                @endif
                            </div>
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold">
                                        {{ $property->property_type }}
                                    </span>
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                        {{ $property->listing_type }}
                                    </span>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $property->name }}</h3>
                                <p class="text-gray-600 mb-4">
                                    <i class="fas fa-map-marker-alt text-purple-600 mr-1"></i>
                                    {{ $property->location }}, {{ $property->city }}
                                </p>
                                
                                @if($property->configuration)
                                    <p class="text-gray-700 mb-4">
                                        <i class="fas fa-home text-purple-600 mr-2"></i>{{ $property->configuration }}
                                    </p>
                                @endif

                                @if($property->carpet_area)
                                    <p class="text-gray-700 mb-4">
                                        <i class="fas fa-ruler-combined text-purple-600 mr-2"></i>{{ $property->carpet_area }} {{ $property->area_unit }}
                                    </p>
                                @endif

                                <div class="border-t pt-4 mb-4">
                                    <p class="text-3xl font-bold gradient-primary bg-clip-text text-transparent">
                                        {{ $property->price_range ?? 'Price on Request' }}
                                    </p>
                                </div>

                                <a href="{{ route('properties.show', $property->id) }}" 
                                   class="block text-center w-full py-3 gradient-primary text-white rounded-lg font-semibold hover:opacity-90 transition">
                                    <i class="fas fa-eye mr-2"></i>View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $properties->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">No Properties Found</h3>
                    <p class="text-gray-600 mb-6">Try adjusting your filters or search criteria</p>
                    <a href="{{ route('properties.index') }}" class="inline-block px-8 py-3 gradient-primary text-white rounded-lg font-semibold hover:opacity-90 transition">
                        <i class="fas fa-redo mr-2"></i>View All Properties
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/919876543210" target="_blank"
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
