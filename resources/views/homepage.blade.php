<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReuseMart - Marketplace Barang Bekas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .animate-stagger {
            animation: slideIn 0.5s ease forwards;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .swiper-pagination-bullet {
            background-color: #f97316; /* Orange-500 */
            opacity: 0.5;
            width: 12px;
            height: 12px;
        }
        .swiper-pagination-bullet-active {
            opacity: 1;
        }
        .swiper-button-prev, .swiper-button-next {
            color: white;
            background-color: rgba(249, 115, 22, 0.8); /* Orange-500 with opacity */
            border-radius: 50%;
            width: 40px;
            height: 40px;
            transition: background-color 0.3s;
        }
        .swiper-button-prev:hover, .swiper-button-next:hover {
            background-color: rgba(249, 115, 22, 1);
        }
        .swiper-button-prev:after, .swiper-button-next:after {
            font-size: 20px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation Bar -->
    <header class="bg-orange-500 text-white sticky top-0 z-10 shadow-md">
        <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="#" class="text-2xl font-bold tracking-tight">ReuseMart</a>
            <div class="flex items-center space-x-6">
                <a href="#" class="hover:text-gray-200 transition">Home</a>
                <a href="#categories" class="hover:text-gray-200 transition">Categories</a>
                <a href="login" class="hover:text-gray-200 transition">Login</a>
                <a href="#register" class="hover:text-gray-200 transition">Register</a>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white py-32 text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://images.pexels.com/photos/209250/pexels-photo-209250.jpeg?auto=compress&cs=tinysrgb&w=1920&h=600&fit=crop')] opacity-10 bg-cover bg-center"></div>
        <div class="relative container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Selamat Datang di ReuseMart</h1>
            <p class="text-lg md:text-xl mb-6 max-w-2xl mx-auto">Marketplace untuk membeli dan menjual barang bekas berkualitas tinggi!</p>
            <div class="flex justify-center space-x-4">
                <a href="login" class="bg-white text-orange-500 py-2 px-6 rounded-lg text-lg font-semibold hover:scale-105 transition-transform duration-300">Login</a>
                <a href="#register" class="border-2 border-white text-white py-2 px-6 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-500 transition-colors duration-300">Daftar</a>
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

    <!-- Footer Section -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-semibold mb-4">ReuseMart</h3>
                    <p class="text-gray-300">Marketplace untuk barang bekas berkualitas, mendukung gaya hidup berkelanjutan.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="" class="text-gray-300 hover:text-orange-500 transition">Home</a></li>
                        <li><a href="#categories" class="text-gray-300 hover:text-orange-500 transition">Categories</a></li>
                        <li><a href="login" class="text-gray-300 hover:text-orange-500 transition">Login</a></li>
                        <li><a href="#register" class="text-gray-300 hover:text-orange-500 transition">Register</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-orange-500 transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.04c-5.5 0-9.96 4.46-9.96 9.96 0 4.95 3.62 9.06 8.36 9.84v-6.96h-2.52v-2.88h2.52v-2.2c0-2.5 1.49-3.87 3.77-3.87 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.85h2.77l-.44 2.88h-2.33v6.96c4.74-.78 8.36-4.89 8.36-9.84 0-5.5-4.46-9.96-9.96-9.96z"/></svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-orange-500 transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.95 4.83c-.88.39-1.83.65-2.82.77 1.01-.61 1.79-1.57 2.16-2.72-.95.56-2 .97-3.12 1.19-.9-.96-2.18-1.56-3.6-1.56-2.72 0-4.93 2.21-4.93 4.93 0 .39.04.77.13 1.13-4.1-.21-7.74-2.17-10.18-5.15-.43.73-.67 1.58-.67 2.49 0 1.72.87 3.24 2.2 4.13-.81-.03-1.57-.25-2.24-.62v.06c0 2.4 1.71 4.4 3.98 4.85-.42.11-.86.17-1.31.17-.32 0-.63-.03-.94-.09.63 1.97 2.46 3.41 4.63 3.45-1.7 1.33-3.83 2.12-6.15 2.12-.4 0-.79-.02-1.18-.07 2.19 1.4 4.78 2.22 7.57 2.22 9.09 0 14.06-7.53 14.06-14.06 0-.21 0-.43-.01-.64.97-.7 1.81-1.58 2.47-2.58z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <p class="text-center text-gray-300 mt-8">© 2025 ReuseMart. Semua Hak Dilindungi.</p>
        </div>
    </footer>

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
                disableOnInteraction: false, // Allows manual sliding without stopping autoplay
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true, // Enables clicking pagination dots to switch slides
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev', // Enables clicking arrows to switch slides
            },
        });
    </script>
</body>
</html>