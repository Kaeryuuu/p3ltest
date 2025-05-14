@extends('layouts.app')

@section('title', 'ReuseMart - Marketplace Barang Bekas')

@section('content')
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white py-32 text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://images.pexels.com/photos/209250/pexels-photo-209250.jpeg?auto=compress&cs=tinysrgb&w=1920&h=600&fit=crop')] opacity-10 bg-cover bg-center"></div>
        <div class="relative container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Selamat Datang di ReuseMart</h1>
            <p class="text-lg md:text-xl mb-6 max-w-2xl mx-auto">Marketplace untuk membeli dan menjual barang bekas berkualitas tinggi!</p>
            <div class="flex justify-center space-x-4">
                @auth('pembeli')
                    <a href="{{ route('pembeli.dashboard') }}" class="bg-white text-orange-500 py-2 px-6 rounded-lg text-lg font-semibold hover:scale-105 transition-transform duration-300">Lihat Profil</a>
                @elseif(auth('organisasi'))
                    <a href="{{ route('organisasi.dashboard') }}" class="bg-white text-orange-500 py-2 px-6 rounded-lg text-lg font-semibold hover:scale-105 transition-transform duration-300">Lihat Profil</a>
                @else
                    <a href="{{ route('login') }}" class="bg-white text-orange-500 py-2 px-6 rounded-lg text-lg font-semibold hover:scale-105 transition-transform duration-300">Login</a>
                    <a href="{{ route('register') }}" class="border-2 border-white text-white py-2 px-6 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-500 transition-colors duration-300">Daftar</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Kategori Produk -->
    <div id="categories" class="container mx-auto my-12 px-4">
        <h2 class="text-2xl md:text-3xl font-semibold text-center mb-8">Kategori Produk</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-lg hover:shadow-xl hover:border-orange-500 border-2 border-transparent transition-all duration-300 relative">
                <span class="absolute top-2 left-2 bg-orange-500 text-white text-xs font-semibold px-2 py-1 rounded">Popular</span>
                <img src="https://images.pexels.com/photos/1350789/pexels-photo-1350789.jpeg?auto=compress&cs=tinysrgb&w=300&h=160&fit=crop" alt="Furniture" class="w-full h-40 object-cover rounded-t-lg">
                <div class="p-4 text-center">
                    <h5 class="text-lg font-semibold">Furniture</h5>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg hover:shadow-xl hover:border-blue-500 border-2 border-transparent transition-all duration-300">
                <img src="https://images.pexels.com/photos/373543/pexels-photo-373543.jpeg?auto=compress&cs=tinysrgb&w=300&h=160&fit=crop" alt="Electronics" class="w-full h-40 object-cover rounded-t-lg">
                <div class="p-4 text-center">
                    <h5 class="text-lg font-semibold">Electronics</h5>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg hover:shadow-xl hover:border-green-500 border-2 border-transparent transition-all duration-300 relative">
                <span class="absolute top-2 left-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">New</span>
                <img src="https://images.pexels.com/photos/135620/pexels-photo-135620.jpeg?auto=compress&cs=tinysrgb&w=300&h=160&fit=crop" alt="Clothing" class="w-full h-40 object-cover rounded-t-lg">
                <div class="p-4 text-center">
                    <h5 class="text-lg font-semibold">Clothing</h5>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg hover:shadow-xl hover:border-purple-500 border-2 border-transparent transition-all duration-300">
                <img src="https://images.pexels.com/photos/325153/pexels-photo-325153.jpeg?auto=compress&cs=tinysrgb&w=300&h=160&fit=crop" alt="Decor" class="w-full h-40 object-cover rounded-t-lg">
                <div class="p-4 text-center">
                    <h5 class="text-lg font-semibold">Decor</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Promo Carousel -->
    <div class="bg-gray-100 py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-semibold text-center mb-8">Promotions</h2>
            <div class="swiper-container shadow-lg rounded-xl overflow-hidden">
                <div class="swiper-wrapper">
                    <div class="swiper-slide relative">
                        <img src="https://images.pexels.com/photos/1866149/pexels-photo-1866149.jpeg?auto=compress&cs=tinysrgb&w=1200&h=400&fit=crop" alt="Promo 1" class="w-full h-80 md:h-96 object-cover scale-100 transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-center justify-center">
                            <h3 class="text-3xl md:text-4xl font-bold text-white text-shadow-lg">20% Off Furniture!</h3>
                        </div>
                    </div>
                    <div class="swiper-slide relative">
                        <img src="https://images.pexels.com/photos/163444/pexels-photo-163444.jpeg?auto=compress&cs=tinysrgb&w=1200&h=400&fit=crop" alt="Promo 2" class="w-full h-80 md:h-96 object-cover scale-100 transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-center justify-center">
                            <h3 class="text-3xl md:text-4xl font-bold text-white text-shadow-lg">Free Shipping!</h3>
                        </div>
                    </div>
                    <div class="swiper-slide relative">
                        <img src="https://images.pexels.com/photos/1488463/pexels-photo-1488463.jpeg?auto=compress&cs=tinysrgb&w=1200&h=400&fit=crop" alt="Promo 3" class="w-full h-80 md:h-96 object-cover scale-100 transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-center justify-center">
                            <h3 class="text-3xl md:text-4xl font-bold text-white text-shadow-lg">Buy 1 Get 1!</h3>
                        </div>
                    </div>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination mt-4"></div>
                <!-- Add Navigation -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </div>

    <!-- Produk yang Bisa Dibeli -->
    <div class="container mx-auto my-12 px-4">
        <h2 class="text-2xl md:text-3xl font-semibold text-center mb-8">Barang Bekas yang Bisa Dibeli</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow-lg hover:scale-105 transition-transform duration-300 animate-stagger relative">
                <span class="absolute top-2 right-2 bg-orange-500 text-white text-sm font-semibold px-2 py-1 rounded">Rp 500.000</span>
                <img src="https://images.pexels.com/photos/133919/pexels-photo-133919.jpeg?auto=compress&cs=tinysrgb&w=300&h=192&fit=crop" alt="Vintage Chair" class="w-full h-48 object-cover rounded-t-lg">
                <div class="p-4">
                    <h5 class="text-xl font-semibold">Vintage Chair</h5>
                    <p class="text-gray-600 mt-2">A beautifully restored vintage chair, perfect for your home.</p>
                    <a href="#product-1" class="bg-orange-500 text-white py-2 px-4 rounded-lg mt-4 inline-block hover:bg-orange-600 transition">Lihat Detail</a>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg hover:scale-105 transition-transform duration-300 animate-stagger relative" style="animation-delay: 0.1s;">
                <span class="absolute top-2 right-2 bg-orange-500 text-white text-sm font-semibold px-2 py-1 rounded">Rp 250.000</span>
                <img src="https://images.pexels.com/photos/1457842/pexels-photo-1457842.jpeg?auto=compress&cs=tinysrgb&w=300&h=192&fit=crop" alt="Retro Lamp" class="w-full h-48 object-cover rounded-t-lg">
                <div class="p-4">
                    <h5 class="text-xl font-semibold">Retro Lamp</h5>
                    <p class="text-gray-600 mt-2">A stylish retro lamp to brighten up your space.</p>
                    <a href="#product-2" class="bg-orange-500 text-white py-2 px-4 rounded-lg mt-4 inline-block hover:bg-orange-600 transition">Lihat Detail</a>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg hover:scale-105 transition-transform duration-300 animate-stagger relative" style="animation-delay: 0.2s;">
                <span class="absolute top-2 right-2 bg-orange-500 text-white text-sm font-semibold px-2 py-1 rounded">Rp 750.000</span>
                <img src="https://images.pexels.com/photos/2079246/pexels-photo-2079246.jpeg?auto=compress&cs=tinysrgb&w=300&h=192&fit=crop" alt="Used Bookshelf" class="w-full h-48 object-cover rounded-t-lg">
                <div class="p-4">
                    <h5 class="text-xl font-semibold">Used Bookshelf</h5>
                    <p class="text-gray-600 mt-2">A sturdy bookshelf in great condition.</p>
                    <a href="#product-3" class="bg-orange-500 text-white py-2 px-4 rounded-lg mt-4 inline-block hover:bg-orange-600 transition">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonial Section -->
    <div class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-semibold text-center mb-8">What Our Customers Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                    <p class="text-gray-600 italic mb-4">"ReuseMart made it so easy to find quality second-hand furniture!"</p>
                    <p class="text-orange-500 font-semibold">Jane Doe</p>
                </div>
                <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                    <p class="text-gray-600 italic mb-4">"Great platform, amazing deals, and fast shipping!"</p>
                    <p class="text-orange-500 font-semibold">John Smith</p>
                </div>
                <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                    <p class="text-gray-600 italic mb-4">"I love the variety of products and the eco-friendly mission."</p>
                    <p class="text-orange-500 font-semibold">Emily Brown</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Swiper Initialization -->
    <script>
        const swiper = new Swiper('.swiper-container', {
            loop: true,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>
@endsection