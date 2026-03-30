<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - ANS Realty</title>
    <meta name="description" content="Get in touch with ANS Realty. We're here to help you find your dream property.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
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
                    <a href="{{ route('contact') }}" class="text-purple-600 font-bold transition">Contact</a>
                    <a href="/admin" class="px-6 py-3 gradient-primary text-white rounded-lg hover:opacity-90 transition font-semibold">Admin Login</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="bg-gradient-to-r from-purple-600 to-blue-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-4">Get In Touch</h1>
            <p class="text-xl text-gray-200">We're here to help you find your dream property</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-3xl font-bold mb-6 text-gray-800">Send Us A Message</h2>
                    
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                            <p class="font-semibold"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('inquiries.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="inquiry_type" value="General">

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Full Name *</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                                   placeholder="Enter your full name">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Mobile Number *</label>
                            <input type="tel" name="mobile" value="{{ old('mobile') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                                   placeholder="Enter your mobile number">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                                   placeholder="Enter your email">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Message</label>
                            <textarea name="message" rows="5"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                                      placeholder="Tell us about your requirements...">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit"
                                class="w-full gradient-primary text-white py-4 rounded-lg font-semibold text-lg hover:opacity-90 transition">
                            <i class="fas fa-paper-plane mr-2"></i>Send Message
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div>
                    <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                        <h2 class="text-3xl font-bold mb-6 text-gray-800">Contact Information</h2>
                        
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="w-12 h-12 gradient-primary rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800 mb-1">Office Address</h3>
                                    <p class="text-gray-600">123, ANS Tower, Bandra West,<br>Mumbai, Maharashtra 400050</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-12 h-12 gradient-primary rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="fas fa-phone text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800 mb-1">Phone Number</h3>
                                    <p class="text-gray-600">+91 9876543210<br>+91 9876543211</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-12 h-12 gradient-primary rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="fas fa-envelope text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800 mb-1">Email Address</h3>
                                    <p class="text-gray-600">info@ansrealty.com<br>support@ansrealty.com</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-12 h-12 gradient-primary rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="fas fa-clock text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800 mb-1">Working Hours</h3>
                                    <p class="text-gray-600">Monday - Saturday: 10:00 AM - 7:00 PM<br>Sunday: Closed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="bg-white rounded-2xl shadow-xl p-4 h-64">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3770.5884347586!2d72.8262!3d19.0596!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTnCsDAzJzM0LjYiTiA3MsKwNDknMzQuMyJF!5e0!3m2!1sen!2sin!4v1234567890"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" class="rounded-lg">
                        </iframe>
                    </div>
                </div>
            </div>
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
                    <p class="text-gray-400">Your trusted partner in finding the perfect property in Mumbai.</p>
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
