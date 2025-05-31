<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - ReUseMart</title>
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
            background-color: #f97316;
            opacity: 0.5;
            width: 12px;
            height: 12px;
        }
        .swiper-pagination-bullet-active {
            opacity: 1;
        }
        .swiper-button-prev, .swiper-button-next {
            color: white;
            background-color: rgba(249, 115, 22, 0.8);
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
<body class="bg-gray-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-orange-500 text-white sticky top-0 z-10 shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="/" class="text-2xl font-bold tracking-tight">ReUseMart</a>
            
            <!-- Navigation Links -->
            <div class="flex items-center space-x-6">
                <a href="/" class="hover:text-gray-200 transition">Home</a>
                <a href="/#categories" class="hover:text-gray-200 transition">Categories</a>
                @if (auth('pembeli')->check())
                    <a href="{{ route('pembeli.dashboard') }}" class="hover:text-gray-200 transition">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="hover:text-gray-200 transition">Logout</button>
                    </form>
                @elseif (auth('organisasi')->check())
                    <a href="{{ route('organisasi.dashboard') }}" class="hover:text-gray-200 transition">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="hover:text-gray-200 transition">Logout</button>
                    </form>
                 @elseif (auth('pegawai')->check())
                    <a href="{{ route('cs.dashboard') }}" class="hover:text-gray-200 transition">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="hover:text-gray-200 transition">Logout</button>
                    </form>
                @elseif (auth('penitip')->check())
                    <a href="{{ route('penitip.dashboard') }}" class="hover:text-gray-200 transition">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="hover:text-gray-200 transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-gray-200 transition">Login</a>
                    <a href="{{ route('register') }}" class="hover:text-gray-200 transition">Register</a>
                @endif

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="container mx-auto px-4">
        @yield('content')
    </main>

    <!-- Footer -->
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
                        <li><a href="/" class="text-gray-300 hover:text-orange-500 transition">Home</a></li>
                        <li><a href="/#categories" class="text-gray-300 hover:text-orange-500 transition">Categories</a></li>
                        @guest('pembeli')
                            @guest('organisasi')
                                <li><a href="{{ route('login') }}" class="text-gray-300 hover:text-orange-500 transition">Login</a></li>
                                <li><a href="{{ route('register') }}" class="text-gray-300 hover:text-orange-500 transition">Register</a></li>
                            @endguest
                        @endguest

                        @auth('organisasi')
                            <li><a href="{{ route('organisasi.dashboard') }}" class="text-gray-300 hover:text-orange-500 transition">Profil</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-gray-300 hover:text-orange-500 transition">Logout</button>
                                </form>
                            </li>
                        @endauth

                        @auth('pembeli')
                            <li><a href="{{ route('pembeli.dashboard') }}" class="text-gray-300 hover:text-orange-500 transition">Profil</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-gray-300 hover:text-orange-500 transition">Logout</button>
                                </form>
                            </li>
                        @endauth

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

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full text-center transform transition-all scale-100">
            <svg class="w-12 h-12 mx-auto mb-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <h3 class="text-xl font-semibold text-green-600 mb-2">Success</h3>
            <p id="successMessage" class="text-gray-600 mb-6"></p>
            <button onclick="hideSuccessModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Close</button>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full text-center transform transition-all scale-100">
            <svg class="w-12 h-12 mx-auto mb-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-red-600 mb-2">Error</h3>
            <p id="errorMessage" class="text-gray-600 mb-6"></p>
            <button onclick="hideErrorModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Close</button>
        </div>
    </div>

    <!-- Deactivate Modal -->
    <div id="deactivateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full text-center transform transition-all scale-100">
            <svg class="w-12 h-12 mx-auto mb-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <h3 class="text-xl font-semibold text-orange-600 mb-2">Deactivated</h3>
            <p id="deactivateMessage" class="text-gray-600 mb-6"></p>
            <button onclick="hideDeactivateModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Close</button>
        </div>
    </div>

    <!-- Activate Modal -->
    <div id="activateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full text-center transform transition-all scale-100">
            <svg class="w-12 h-12 mx-auto mb-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-blue-600 mb-2">Activated</h3>
            <p id="activateMessage" class="text-gray-600 mb-6"></p>
            <button onclick="hideActivateModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Close</button>
        </div>
    </div>

    <style>
        @keyframes modal-appear {
            0% { transform: scale(0.7); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes modal-disappear {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(0.7); opacity: 0; }
        }
        .modal-appear {
            animation: modal-appear 0.3s ease-out;
        }
        .modal-disappear {
            animation: modal-disappear 0.3s ease-in forwards;
        }
    </style>

    <script>
        function showSuccessModal(message) {
            const modal = document.getElementById('successModal');
            const messageEl = document.getElementById('successMessage');
            messageEl.textContent = message;
            modal.classList.remove('hidden');
            modal.querySelector('div').classList.add('modal-appear');
            setTimeout(() => hideSuccessModal(), 3000);
        }

        function hideSuccessModal() {
            const modal = document.getElementById('successModal');
            const content = modal.querySelector('div');
            content.classList.remove('modal-appear');
            content.classList.add('modal-disappear');
            setTimeout(() => {
                modal.classList.add('hidden');
                content.classList.remove('modal-disappear');
            }, 300);
        }

        function showErrorModal(message) {
            const modal = document.getElementById('errorModal');
            const messageEl = document.getElementById('errorMessage');
            messageEl.textContent = message;
            modal.classList.remove('hidden');
            modal.querySelector('div').classList.add('modal-appear');
            setTimeout(() => hideErrorModal(), 3000);
        }

        function hideErrorModal() {
            const modal = document.getElementById('errorModal');
            const content = modal.querySelector('div');
            content.classList.remove('modal-appear');
            content.classList.add('modal-disappear');
            setTimeout(() => {
                modal.classList.add('hidden');
                content.classList.remove('modal-disappear');
            }, 300);
        }

        function showDeactivateModal(message) {
            const modal = document.getElementById('deactivateModal');
            const messageEl = document.getElementById('deactivateMessage');
            messageEl.textContent = message;
            modal.classList.remove('hidden');
            modal.querySelector('div').classList.add('modal-appear');
            setTimeout(() => hideDeactivateModal(), 3000);
        }

        function hideDeactivateModal() {
            const modal = document.getElementById('deactivateModal');
            const content = modal.querySelector('div');
            content.classList.remove('modal-appear');
            content.classList.add('modal-disappear');
            setTimeout(() => {
                modal.classList.add('hidden');
                content.classList.remove('modal-disappear');
            }, 300);
        }

        function showActivateModal(message) {
            const modal = document.getElementById('activateModal');
            const messageEl = document.getElementById('activateMessage');
            messageEl.textContent = message;
            modal.classList.remove('hidden');
            modal.querySelector('div').classList.add('modal-appear');
            setTimeout(() => hideActivateModal(), 3000);
        }

        function hideActivateModal() {
            const modal = document.getElementById('activateModal');
            const content = modal.querySelector('div');
            content.classList.remove('modal-appear');
            content.classList.add('modal-disappear');
            setTimeout(() => {
                modal.classList.add('hidden');
                content.classList.remove('modal-disappear');
            }, 300);
        }
    </script>
</body>
</html>