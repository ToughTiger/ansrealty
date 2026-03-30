<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ANS Realty - Your Dream Property Awaits</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .property-card img {
            transition: transform 0.5s ease;
        }
        .property-card:hover img {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center">
                    <div class="w-12 h-12 gradient-primary rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-building text-white text-2xl"></i>
                    </div>
                    <span class="text-3xl font-bold text-gray-800">ANS Realty</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-gray-700 hover:text-purple-600 font-medium transition">Home</a>
                    <a href="/properties" class="text-gray-700 hover:text-purple-600 font-medium transition">Properties</a>
                    <a href="/about" class="text-gray-700 hover:text-purple-600 font-medium transition">About</a>
                    <a href="/contact" class="text-gray-700 hover:text-purple-600 font-medium transition">Contact</a>
                    <a href="/admin" class="px-6 py-3 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold">Admin Login</a>
                </div>
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-gray-700">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative bg-cover bg-center h-screen" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1920');">
        <div class="max-w-7xl mx-auto px-4 h-full flex items-center">
            <div class="text-white max-w-3xl">
                <h1 class="text-6xl font-bold mb-6 leading-tight">Discover Your Perfect <span class="text-yellow-400">Dream Home</span></h1>
                <p class="text-2xl mb-8 text-gray-200">Exclusive properties in prime locations. Start your journey to homeownership today.</p>
                <div class="flex gap-4">
                    <a href="/properties" class="px-8 py-4 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold text-lg inline-flex items-center">
                        <i class="fas fa-search mr-2"></i> Browse Properties
                    </a>
                    <a href="/featured-project" class="px-8 py-4 bg-yellow-400 text-gray-900 rounded-lg hover:bg-yellow-300 transition font-semibold text-lg inline-flex items-center">
                        <i class="fas fa-star mr-2"></i> Featured Project
                    </a>
                    <a href="#consultation" class="px-8 py-4 bg-white text-purple-600 rounded-lg hover:bg-gray-100 transition font-semibold text-lg inline-flex items-center">
                        <i class="fas fa-calendar-check mr-2"></i> Book Consultation
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Property Search Section -->
    <section class="relative -mt-20 z-30 pb-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-2xl p-8 border-t-4 border-purple-600">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Find Your Dream Property</h3>
                <div class="grid md:grid-cols-5 gap-4">
                    <!-- Location Filter -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-1"></i>Location
                        </label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                            <option value="">All Locations</option>
                            <option>Bandra West</option>
                            <option>Bandra East</option>
                            <option>Andheri West</option>
                            <option>Andheri East</option>
                            <option>Juhu</option>
                            <option>Powai</option>
                            <option>Lower Parel</option>
                            <option>Marine Drive</option>
                            <option>Worli</option>
                            <option>Goregaon</option>
                            <option>Malad</option>
                            <option>Kandivali</option>
                        </select>
                    </div>

                    <!-- Property Type Filter -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-home text-purple-600 mr-1"></i>Property Type
                        </label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                            <option value="">All Types</option>
                            <option>Apartment</option>
                            <option>Villa</option>
                            <option>Penthouse</option>
                            <option>Plot</option>
                            <option>Commercial</option>
                            <option>Studio</option>
                        </select>
                    </div>

                    <!-- BHK Filter -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-bed text-purple-600 mr-1"></i>BHK
                        </label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                            <option value="">Any</option>
                            <option>1 BHK</option>
                            <option>2 BHK</option>
                            <option>3 BHK</option>
                            <option>4 BHK</option>
                            <option>5+ BHK</option>
                        </select>
                    </div>

                    <!-- Budget Filter -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-rupee-sign text-purple-600 mr-1"></i>Budget
                        </label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                            <option value="">Any Budget</option>
                            <option>Under ₹50 L</option>
                            <option>₹50 L - ₹1 Cr</option>
                            <option>₹1 Cr - ₹2 Cr</option>
                            <option>₹2 Cr - ₹5 Cr</option>
                            <option>Above ₹5 Cr</option>
                        </select>
                    </div>

                    <!-- Search Button -->
                    <div class="flex items-end">
                        <a href="/properties" class="w-full py-3 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold text-center block">
                            <i class="fas fa-search mr-2"></i>Search
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-5xl font-bold text-purple-600 mb-2">500+</div>
                    <p class="text-gray-600 text-lg">Properties Listed</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-purple-600 mb-2">1200+</div>
                    <p class="text-gray-600 text-lg">Happy Customers</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-purple-600 mb-2">50+</div>
                    <p class="text-gray-600 text-lg">Expert Agents</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-purple-600 mb-2">15+</div>
                    <p class="text-gray-600 text-lg">Prime Locations</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Properties Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <span class="text-purple-600 font-semibold text-lg">PREMIUM SELECTION</span>
                <h2 class="text-5xl font-bold text-gray-800 mt-2">Featured Properties</h2>
                <p class="text-gray-600 mt-4 text-xl">Handpicked properties that match your dreams</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <!-- Property 1 -->
                <div class="property-card bg-white rounded-2xl overflow-hidden shadow-lg hover-lift">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600" alt="Luxury Sky Villa" class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-yellow-400 text-gray-900 px-3 py-1 rounded-full font-bold text-xs">FEATURED</span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full font-bold text-xs">READY TO MOVE</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Luxury Sky Villa</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                            Bandra West, Mumbai
                        </p>
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex gap-4 text-sm text-gray-600">
                                <span><i class="fas fa-bed mr-1"></i>4 BHK</span>
                                <span><i class="fas fa-bath mr-1"></i>3 Bath</span>
                                <span><i class="fas fa-ruler-combined mr-1"></i>2,800 sq.ft</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t">
                            <div>
                                <p class="text-sm text-gray-600">Price</p>
                                <p class="text-2xl font-bold text-purple-600">₹2.5 Cr</p>
                            </div>
                            <a href="/featured-project" class="px-6 py-3 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold text-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Property 2 -->
                <div class="property-card bg-white rounded-2xl overflow-hidden shadow-lg hover-lift">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=600" alt="Modern Apartment" class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-yellow-400 text-gray-900 px-3 py-1 rounded-full font-bold text-xs">FEATURED</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Modern Apartment</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                            Powai, Mumbai
                        </p>
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex gap-4 text-sm text-gray-600">
                                <span><i class="fas fa-bed mr-1"></i>3 BHK</span>
                                <span><i class="fas fa-bath mr-1"></i>2 Bath</span>
                                <span><i class="fas fa-ruler-combined mr-1"></i>1,850 sq.ft</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t">
                            <div>
                                <p class="text-sm text-gray-600">Price</p>
                                <p class="text-2xl font-bold text-purple-600">₹1.8 Cr</p>
                            </div>
                            <a href="/featured-project" class="px-6 py-3 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold text-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Property 3 -->
                <div class="property-card bg-white rounded-2xl overflow-hidden shadow-lg hover-lift">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=600" alt="Garden Villa" class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-yellow-400 text-gray-900 px-3 py-1 rounded-full font-bold text-xs">FEATURED</span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <span class="bg-blue-500 text-white px-3 py-1 rounded-full font-bold text-xs">NEW LAUNCH</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Garden Villa</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                            Juhu, Mumbai
                        </p>
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex gap-4 text-sm text-gray-600">
                                <span><i class="fas fa-bed mr-1"></i>5 BHK</span>
                                <span><i class="fas fa-bath mr-1"></i>4 Bath</span>
                                <span><i class="fas fa-ruler-combined mr-1"></i>3,500 sq.ft</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t">
                            <div>
                                <p class="text-sm text-gray-600">Price</p>
                                <p class="text-2xl font-bold text-purple-600">₹4.2 Cr</p>
                            </div>
                            <a href="/featured-project" class="px-6 py-3 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold text-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="/properties" class="px-10 py-4 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold text-lg inline-flex items-center">
                    View All Properties <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <span class="text-purple-600 font-semibold text-lg">WHY CHOOSE US</span>
                <h2 class="text-5xl font-bold text-gray-800 mt-2">Your Trusted Real Estate Partner</h2>
                <p class="text-gray-600 mt-4 text-xl">Experience excellence in property services</p>
            </div>
            
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center p-6 bg-white rounded-xl shadow hover:shadow-lg transition">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-home text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Wide Selection</h3>
                    <p class="text-gray-600">Thousands of verified properties across prime locations</p>
                </div>
                <div class="text-center p-6 bg-white rounded-xl shadow hover:shadow-lg transition">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Trusted Service</h3>
                    <p class="text-gray-600">100% verified listings with complete legal documentation</p>
                </div>
                <div class="text-center p-6 bg-white rounded-xl shadow hover:shadow-lg transition">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">24/7 Support</h3>
                    <p class="text-gray-600">Always here to help you find your perfect home</p>
                </div>
                <div class="text-center p-6 bg-white rounded-xl shadow hover:shadow-lg transition">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-award text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Expert Guidance</h3>
                    <p class="text-gray-600">Professional agents with years of market experience</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Hot Properties Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <span class="text-red-600 font-semibold text-lg flex items-center justify-center">
                    <i class="fas fa-fire mr-2"></i>HOT PROPERTIES
                </span>
                <h2 class="text-5xl font-bold text-gray-800 mt-2">Trending This Week</h2>
                <p class="text-gray-600 mt-4 text-xl">Most viewed properties in high demand</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <!-- Hot Property 1 -->
                <div class="property-card bg-white rounded-2xl overflow-hidden shadow-lg hover-lift">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=600" alt="Seaside Penthouse" class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full font-bold text-xs flex items-center">
                                <i class="fas fa-fire mr-1"></i>HOT
                            </span>
                        </div>
                        <div class="absolute top-4 right-4 bg-black bg-opacity-70 px-3 py-1 rounded-full">
                            <span class="text-white text-xs font-bold">
                                <i class="fas fa-eye mr-1"></i>1.2k views
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Seaside Penthouse</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                            Marine Drive, Mumbai
                        </p>
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex gap-4 text-sm text-gray-600">
                                <span><i class="fas fa-bed mr-1"></i>4 BHK</span>
                                <span><i class="fas fa-bath mr-1"></i>4 Bath</span>
                                <span><i class="fas fa-ruler-combined mr-1"></i>3,200 sq.ft</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t">
                            <div>
                                <p class="text-sm text-gray-600">Price</p>
                                <p class="text-2xl font-bold text-purple-600">₹5.8 Cr</p>
                            </div>
                            <a href="/featured-project" class="px-6 py-3 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold text-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Hot Property 2 -->
                <div class="property-card bg-white rounded-2xl overflow-hidden shadow-lg hover-lift">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=600" alt="Smart Home Apartment" class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full font-bold text-xs flex items-center">
                                <i class="fas fa-fire mr-1"></i>HOT
                            </span>
                        </div>
                        <div class="absolute top-4 right-4 bg-black bg-opacity-70 px-3 py-1 rounded-full">
                            <span class="text-white text-xs font-bold">
                                <i class="fas fa-eye mr-1"></i>980 views
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Smart Home Apartment</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                            Lower Parel, Mumbai
                        </p>
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex gap-4 text-sm text-gray-600">
                                <span><i class="fas fa-bed mr-1"></i>2 BHK</span>
                                <span><i class="fas fa-bath mr-1"></i>2 Bath</span>
                                <span><i class="fas fa-ruler-combined mr-1"></i>1,450 sq.ft</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t">
                            <div>
                                <p class="text-sm text-gray-600">Price</p>
                                <p class="text-2xl font-bold text-purple-600">₹2.1 Cr</p>
                            </div>
                            <a href="/featured-project" class="px-6 py-3 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold text-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Hot Property 3 -->
                <div class="property-card bg-white rounded-2xl overflow-hidden shadow-lg hover-lift">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=600" alt="Duplex Villa" class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full font-bold text-xs flex items-center">
                                <i class="fas fa-fire mr-1"></i>HOT
                            </span>
                        </div>
                        <div class="absolute top-4 right-4 bg-black bg-opacity-70 px-3 py-1 rounded-full">
                            <span class="text-white text-xs font-bold">
                                <i class="fas fa-eye mr-1"></i>850 views
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Duplex Villa</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                            Andheri West, Mumbai
                        </p>
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex gap-4 text-sm text-gray-600">
                                <span><i class="fas fa-bed mr-1"></i>3 BHK</span>
                                <span><i class="fas fa-bath mr-1"></i>3 Bath</span>
                                <span><i class="fas fa-ruler-combined mr-1"></i>2,400 sq.ft</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t">
                            <div>
                                <p class="text-sm text-gray-600">Price</p>
                                <p class="text-2xl font-bold text-purple-600">₹3.2 Cr</p>
                            </div>
                            <a href="/featured-project" class="px-6 py-3 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold text-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="/properties" class="px-10 py-4 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold text-lg inline-flex items-center">
                    View All Properties <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Consultation + Featured Project Section -->
    <section id="consultation" class="py-20 bg-gradient-to-br from-purple-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left Side - Contact Form -->
                <div class="bg-white rounded-2xl p-8 shadow-xl">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Claim Your Exclusive Consultation</h2>
                    <p class="text-gray-600 mb-8">Fill out the form to connect with one of our top agents. This is a limited-time offer for our new clients.</p>
                    
                    <form action="#" method="POST" class="space-y-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Full Name *</label>
                            <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent" placeholder="John Doe">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Phone Number *</label>
                            <input type="tel" name="phone" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent" placeholder="+91 98765 43210">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Email Address *</label>
                            <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent" placeholder="john@example.com">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Budget Range</label>
                            <select name="budget" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                                <option>Under ₹50 Lakhs</option>
                                <option>₹50 Lakhs - ₹1 Crore</option>
                                <option>₹1 Crore - ₹2 Crore</option>
                                <option>₹2 Crore - ₹5 Crore</option>
                                <option>Above ₹5 Crore</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Message (Optional)</label>
                            <textarea name="message" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent" placeholder="Tell us about your requirements..."></textarea>
                        </div>
                        
                        <button type="submit" class="w-full py-4 gradient-primary text-white rounded-lg hover:opacity-90 transition font-bold text-lg">
                            <i class="fas fa-paper-plane mr-2"></i>Get Free Consultation
                        </button>
                        
                        <p class="text-xs text-gray-500 text-center">By submitting this form, you agree to our Terms & Conditions and Privacy Policy.</p>
                    </form>
                </div>

                <!-- Right Side - Featured Project -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-xl">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800" alt="Luxury Sky Villa Project" class="w-full h-80 object-cover">
                        <div class="absolute top-6 left-6">
                            <span class="bg-yellow-400 text-gray-900 px-4 py-2 rounded-full font-bold">SPECIAL OFFER</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-3xl font-bold text-gray-800 mb-3">Luxury Sky Villa - Bandra West</h3>
                        <p class="text-gray-600 mb-6">Limited time offer: Get exclusive pricing and premium amenities worth ₹50 Lakhs absolutely FREE!</p>
                        
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-bed text-purple-600 text-xl mb-1"></i>
                                <p class="text-xs text-gray-600">Bedrooms</p>
                                <p class="font-bold">4 BHK</p>
                            </div>
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-bath text-purple-600 text-xl mb-1"></i>
                                <p class="text-xs text-gray-600">Bathrooms</p>
                                <p class="font-bold">3</p>
                            </div>
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-ruler-combined text-purple-600 text-xl mb-1"></i>
                                <p class="text-xs text-gray-600">Area</p>
                                <p class="font-bold">2,800 sq.ft</p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-purple-100 to-blue-100 rounded-xl p-6 mb-6">
                            <p class="text-sm text-gray-700 mb-2">Special Launch Price</p>
                            <p class="text-4xl font-bold text-purple-600 mb-2">₹2.5 Cr</p>
                            <p class="text-sm text-gray-600"><span class="line-through">₹3 Cr</span> <span class="text-green-600 font-bold">Save ₹50 Lakhs!</span></p>
                        </div>

                        <a href="/featured-project" class="block w-full py-4 gradient-primary text-white rounded-lg hover:opacity-90 transition font-bold text-center text-lg">
                            <i class="fas fa-star mr-2"></i>View Project Details
                        </a>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                <i class="fas fa-swimming-pool mr-1"></i>Pool
                            </span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                <i class="fas fa-dumbbell mr-1"></i>Gym
                            </span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                <i class="fas fa-car mr-1"></i>Parking
                            </span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                <i class="fas fa-shield-alt mr-1"></i>24/7 Security
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12">Browse by Type</h2>
            <div class="grid md:grid-cols-4 gap-6">
                <a href="/properties?type=apartment" class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl p-8 text-white text-center hover:scale-105 transition">
                    <i class="fas fa-building text-5xl mb-4"></i>
                    <h3 class="text-xl font-bold">Apartments</h3>
                </a>
                <a href="/properties?type=villa" class="bg-gradient-to-br from-green-500 to-teal-500 rounded-xl p-8 text-white text-center hover:scale-105 transition">
                    <i class="fas fa-home text-5xl mb-4"></i>
                    <h3 class="text-xl font-bold">Villas</h3>
                </a>
                <a href="/properties?type=plot" class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl p-8 text-white text-center hover:scale-105 transition">
                    <i class="fas fa-map-marked-alt text-5xl mb-4"></i>
                    <h3 class="text-xl font-bold">Plots</h3>
                </a>
                <a href="/properties?type=commercial" class="bg-gradient-to-br from-red-500 to-pink-500 rounded-xl p-8 text-white text-center hover:scale-105 transition">
                    <i class="fas fa-store text-5xl mb-4"></i>
                    <h3 class="text-xl font-bold">Commercial</h3>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <!-- Column 1: Logo & Tagline -->
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 gradient-primary rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-building text-white text-2xl"></i>
                        </div>
                        <span class="text-2xl font-bold">ANS Realty</span>
                    </div>
                    <p class="text-gray-400 mb-6">Your trusted property partner for finding dream homes in prime locations. Excellence in real estate since 2020.</p>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Column 2: Quick Links -->
                <div>
                    <h3 class="font-bold text-lg mb-6 border-b border-gray-800 pb-3">Quick Links</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li>
                            <a href="/" class="hover:text-purple-400 transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>Home
                            </a>
                        </li>
                        <li>
                            <a href="/properties" class="hover:text-purple-400 transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>Properties
                            </a>
                        </li>
                        <li>
                            <a href="/featured-project" class="hover:text-purple-400 transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>Featured Project
                            </a>
                        </li>
                        <li>
                            <a href="/about" class="hover:text-purple-400 transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>About Us
                            </a>
                        </li>
                        <li>
                            <a href="/contact" class="hover:text-purple-400 transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>Contact
                            </a>
                        </li>
                        <li>
                            <a href="/admin" class="hover:text-purple-400 transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>Admin Login
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Column 3: Legal -->
                <div>
                    <h3 class="font-bold text-lg mb-6 border-b border-gray-800 pb-3">Legal</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li>
                            <a href="#" class="hover:text-purple-400 transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>Privacy Policy
                            </a>
                        </li>
                        <li>
                            <a href="#" class="hover:text-purple-400 transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>Terms & Conditions
                            </a>
                        </li>
                        <li>
                            <a href="#" class="hover:text-purple-400 transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>Cookie Policy
                            </a>
                        </li>
                        <li>
                            <a href="#" class="hover:text-purple-400 transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>RERA Compliance
                            </a>
                        </li>
                        <li>
                            <a href="#" class="hover:text-purple-400 transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>Refund Policy
                            </a>
                        </li>
                        <li>
                            <a href="#" class="hover:text-purple-400 transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>Disclaimer
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Column 4: Contact Info -->
                <div>
                    <h3 class="font-bold text-lg mb-6 border-b border-gray-800 pb-3">Get in Touch</h3>
                    <ul class="space-y-4 text-gray-400">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt text-purple-600 mt-1 mr-3"></i>
                            <span>C-304, Parmeshwaram, Kalyan East, Mumbai - 421306</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone text-purple-600 mr-3"></i>
                            <a href="tel:+919876543210" class="hover:text-purple-400 transition">+91 94227 99527</a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope text-purple-600 mr-3"></i>
                            <a href="mailto:info@ansrealty.com" class="hover:text-purple-400 transition">info@ansrealtycorp.com</a>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-clock text-purple-600 mt-1 mr-3"></i>
                            <span>Mon - Sat: 9:00 AM - 8:00 PM<br>Sunday: 10:00 AM - 6:00 PM</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Bottom Bar -->
            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center text-gray-400 text-sm">
                    <p>&copy; 2025 ANS Realty. All rights reserved. Designed with <i class="fas fa-heart text-red-500"></i> for dreamers.</p>
                    <div class="flex gap-6 mt-4 md:mt-0">
                        <a href="#" class="hover:text-purple-400 transition">Sitemap</a>
                        <a href="#" class="hover:text-purple-400 transition">Careers</a>
                        <a href="#" class="hover:text-purple-400 transition">Blog</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <a href="https://wa.me/919876543210" class="fixed bottom-6 right-6 bg-green-500 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg hover:bg-green-600 z-50">
        <i class="fab fa-whatsapp text-3xl"></i>
    </a>
</body>
</html>
