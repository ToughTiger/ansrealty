<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties - Find Your Dream Home | ANS Realty</title>
    <meta name="description" content="Browse through our wide selection of properties in Mumbai. Find apartments, villas, plots, and commercial spaces in your preferred location.">
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
            transform: translateY(-5px);
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
                <a href="/" class="flex items-center">
                    <div class="w-12 h-12 gradient-primary rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-building text-white text-2xl"></i>
                    </div>
                    <span class="text-3xl font-bold text-gray-800">ANS Realty</span>
                </a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-gray-700 hover:text-purple-600 font-medium transition">Home</a>
                    <a href="/properties" class="text-purple-600 hover:text-purple-600 font-bold transition">Properties</a>
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

    <!-- Page Header -->
    <section class="bg-gradient-to-r from-purple-600 to-blue-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-5xl font-bold mb-4">Explore Properties</h1>
            <p class="text-xl text-gray-200">Find your perfect home from our extensive collection</p>
        </div>
    </section>

    <!-- Search and Filters Section -->
    <section class="py-8 bg-white shadow-md sticky top-20 z-40">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-white rounded-xl p-6 border-2 border-gray-200">
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
                        <button class="w-full py-3 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                    </div>
                </div>

                <!-- Advanced Filters Toggle -->
                <div class="mt-4 pt-4 border-t">
                    <button class="text-purple-600 font-semibold hover:text-purple-700 transition text-sm">
                        <i class="fas fa-sliders-h mr-2"></i>Advanced Filters
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Results Section -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Results Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Found 24 Properties</h2>
                    <p class="text-gray-600">Based on your preferences</p>
                </div>
                <div class="flex items-center gap-4">
                    <label class="text-gray-700 font-semibold text-sm">Sort By:</label>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600">
                        <option>Relevance</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Newest First</option>
                        <option>Most Popular</option>
                    </select>
                </div>
            </div>

            <!-- Properties Grid -->
            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <!-- Property Card 1 -->
                <div class="property-card bg-white rounded-2xl overflow-hidden shadow-lg hover-lift">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600" alt="Luxury Sky Villa" class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-yellow-400 text-gray-900 px-3 py-1 rounded-full font-bold text-xs">FEATURED</span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <button class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow hover:bg-gray-100">
                                <i class="far fa-heart text-purple-600"></i>
                            </button>
                        </div>
                        <div class="absolute bottom-4 left-4">
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full font-bold text-xs">READY TO MOVE</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Luxury Sky Villa</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                            Bandra West, Mumbai
                        </p>
                        <div class="flex justify-between items-center mb-4 text-sm text-gray-600">
                            <span><i class="fas fa-bed mr-1"></i>4 BHK</span>
                            <span><i class="fas fa-bath mr-1"></i>3 Bath</span>
                            <span><i class="fas fa-ruler-combined mr-1"></i>2,800 sq.ft</span>
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

                <!-- Property Card 2 -->
                <div class="property-card bg-white rounded-2xl overflow-hidden shadow-lg hover-lift">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=600" alt="Modern Apartment" class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4">
                            <button class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow hover:bg-gray-100">
                                <i class="far fa-heart text-purple-600"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Modern Apartment</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                            Powai, Mumbai
                        </p>
                        <div class="flex justify-between items-center mb-4 text-sm text-gray-600">
                            <span><i class="fas fa-bed mr-1"></i>3 BHK</span>
                            <span><i class="fas fa-bath mr-1"></i>2 Bath</span>
                            <span><i class="fas fa-ruler-combined mr-1"></i>1,850 sq.ft</span>
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

                <!-- Property Card 3 -->
                <div class="property-card bg-white rounded-2xl overflow-hidden shadow-lg hover-lift">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=600" alt="Garden Villa" class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-blue-500 text-white px-3 py-1 rounded-full font-bold text-xs">NEW LAUNCH</span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <button class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow hover:bg-gray-100">
                                <i class="far fa-heart text-purple-600"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Garden Villa</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                            Juhu, Mumbai
                        </p>
                        <div class="flex justify-between items-center mb-4 text-sm text-gray-600">
                            <span><i class="fas fa-bed mr-1"></i>5 BHK</span>
                            <span><i class="fas fa-bath mr-1"></i>4 Bath</span>
                            <span><i class="fas fa-ruler-combined mr-1"></i>3,500 sq.ft</span>
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

                <!-- Property Card 4 -->
                <div class="property-card bg-white rounded-2xl overflow-hidden shadow-lg hover-lift">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=600" alt="Seaside Penthouse" class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4">
                            <button class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow hover:bg-gray-100">
                                <i class="far fa-heart text-purple-600"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Seaside Penthouse</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                            Marine Drive, Mumbai
                        </p>
                        <div class="flex justify-between items-center mb-4 text-sm text-gray-600">
                            <span><i class="fas fa-bed mr-1"></i>4 BHK</span>
                            <span><i class="fas fa-bath mr-1"></i>4 Bath</span>
                            <span><i class="fas fa-ruler-combined mr-1"></i>3,200 sq.ft</span>
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

                <!-- Property Card 5 -->
                <div class="property-card bg-white rounded-2xl overflow-hidden shadow-lg hover-lift">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=600" alt="Smart Home Apartment" class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4">
                            <button class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow hover:bg-gray-100">
                                <i class="far fa-heart text-purple-600"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Smart Home Apartment</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                            Lower Parel, Mumbai
                        </p>
                        <div class="flex justify-between items-center mb-4 text-sm text-gray-600">
                            <span><i class="fas fa-bed mr-1"></i>2 BHK</span>
                            <span><i class="fas fa-bath mr-1"></i>2 Bath</span>
                            <span><i class="fas fa-ruler-combined mr-1"></i>1,450 sq.ft</span>
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

                <!-- Property Card 6 -->
                <div class="property-card bg-white rounded-2xl overflow-hidden shadow-lg hover-lift">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=600" alt="Duplex Villa" class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4">
                            <button class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow hover:bg-gray-100">
                                <i class="far fa-heart text-purple-600"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Duplex Villa</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                            Andheri West, Mumbai
                        </p>
                        <div class="flex justify-between items-center mb-4 text-sm text-gray-600">
                            <span><i class="fas fa-bed mr-1"></i>3 BHK</span>
                            <span><i class="fas fa-bath mr-1"></i>3 Bath</span>
                            <span><i class="fas fa-ruler-combined mr-1"></i>2,400 sq.ft</span>
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

                <!-- Property Card 7 -->
                <div class="property-card bg-white rounded-2xl overflow-hidden shadow-lg hover-lift">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=600" alt="Luxury Apartment" class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4">
                            <button class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow hover:bg-gray-100">
                                <i class="far fa-heart text-purple-600"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Luxury Apartment</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                            Worli, Mumbai
                        </p>
                        <div class="flex justify-between items-center mb-4 text-sm text-gray-600">
                            <span><i class="fas fa-bed mr-1"></i>3 BHK</span>
                            <span><i class="fas fa-bath mr-1"></i>3 Bath</span>
                            <span><i class="fas fa-ruler-combined mr-1"></i>2,100 sq.ft</span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t">
                            <div>
                                <p class="text-sm text-gray-600">Price</p>
                                <p class="text-2xl font-bold text-purple-600">₹3.8 Cr</p>
                            </div>
                            <a href="/featured-project" class="px-6 py-3 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold text-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Property Card 8 -->
                <div class="property-card bg-white rounded-2xl overflow-hidden shadow-lg hover-lift">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1600585154084-4e5fe7c39198?w=600" alt="Studio Apartment" class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full font-bold text-xs">AFFORDABLE</span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <button class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow hover:bg-gray-100">
                                <i class="far fa-heart text-purple-600"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Studio Apartment</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                            Goregaon, Mumbai
                        </p>
                        <div class="flex justify-between items-center mb-4 text-sm text-gray-600">
                            <span><i class="fas fa-bed mr-1"></i>1 BHK</span>
                            <span><i class="fas fa-bath mr-1"></i>1 Bath</span>
                            <span><i class="fas fa-ruler-combined mr-1"></i>650 sq.ft</span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t">
                            <div>
                                <p class="text-sm text-gray-600">Price</p>
                                <p class="text-2xl font-bold text-purple-600">₹75 L</p>
                            </div>
                            <a href="/featured-project" class="px-6 py-3 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold text-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Property Card 9 -->
                <div class="property-card bg-white rounded-2xl overflow-hidden shadow-lg hover-lift">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1600573472591-ee6b68d14c68?w=600" alt="Cozy 2BHK" class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4">
                            <button class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow hover:bg-gray-100">
                                <i class="far fa-heart text-purple-600"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Cozy 2BHK</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                            Malad, Mumbai
                        </p>
                        <div class="flex justify-between items-center mb-4 text-sm text-gray-600">
                            <span><i class="fas fa-bed mr-1"></i>2 BHK</span>
                            <span><i class="fas fa-bath mr-1"></i>2 Bath</span>
                            <span><i class="fas fa-ruler-combined mr-1"></i>1,100 sq.ft</span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t">
                            <div>
                                <p class="text-sm text-gray-600">Price</p>
                                <p class="text-2xl font-bold text-purple-600">₹1.2 Cr</p>
                            </div>
                            <a href="/featured-project" class="px-6 py-3 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold text-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center items-center gap-2">
                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="px-4 py-2 bg-purple-600 text-white rounded-lg font-semibold">1</button>
                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">2</button>
                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">3</button>
                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <i class="fas fa-chevron-right"></i>
                </button>
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
                    <p class="text-gray-400 mb-6">Your trusted property partner for finding dream homes in prime locations.</p>
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
                        <li><a href="/" class="hover:text-purple-400 transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Home</a></li>
                        <li><a href="/properties" class="hover:text-purple-400 transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Properties</a></li>
                        <li><a href="/featured-project" class="hover:text-purple-400 transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Featured Project</a></li>
                        <li><a href="/about" class="hover:text-purple-400 transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>About Us</a></li>
                        <li><a href="/contact" class="hover:text-purple-400 transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Contact</a></li>
                    </ul>
                </div>
                
                <!-- Column 3: Legal -->
                <div>
                    <h3 class="font-bold text-lg mb-6 border-b border-gray-800 pb-3">Legal</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="#" class="hover:text-purple-400 transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-purple-400 transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Terms & Conditions</a></li>
                        <li><a href="#" class="hover:text-purple-400 transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Cookie Policy</a></li>
                        <li><a href="#" class="hover:text-purple-400 transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>RERA Compliance</a></li>
                    </ul>
                </div>
                
                <!-- Column 4: Contact -->
                <div>
                    <h3 class="font-bold text-lg mb-6 border-b border-gray-800 pb-3">Get in Touch</h3>
                    <ul class="space-y-4 text-gray-400">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt text-purple-600 mt-1 mr-3"></i>
                            <span>Bandra West, Mumbai - 400050</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone text-purple-600 mr-3"></i>
                            <a href="tel:+919876543210" class="hover:text-purple-400 transition">+91 98765 43210</a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope text-purple-600 mr-3"></i>
                            <a href="mailto:info@ansrealty.com" class="hover:text-purple-400 transition">info@ansrealty.com</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Bottom Bar -->
            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-gray-400 text-sm">&copy; 2025 ANS Realty. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/919876543210" class="fixed bottom-6 right-6 bg-green-500 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg hover:bg-green-600 z-50">
        <i class="fab fa-whatsapp text-3xl"></i>
    </a>
</body>
</html>
