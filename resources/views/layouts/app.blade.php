
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ReUseMart')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow">
        <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="text-xl font-semibold text-blue-600">ReUseMart</div>
            <ul class="flex space-x-4">
                <li><a href="#" class="text-gray-600 hover:text-blue-600">Home</a></li>
                <li><a href="#" class="text-gray-600 hover:text-blue-600">Categories</a></li>
                @if (auth('pembeli')->check() || auth('organisasi')->check() || auth('pegawai')->check() || auth('penitip')->check())
                    <li><a href="#" class="text-gray-600 hover:text-blue-600">Profil</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-blue-600">Logout</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600">Login</a></li>
                    <li><a href="{{ route('register') }}" class="text-gray-600 hover:text-blue-600">Register</a></li>
                @endif
            </ul>
        </nav>
    </header>

    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-lg font-semibold">ReuseMart</h3>
                <p>Marketplace untuk barang bekas berkualitas, mendukung gaya hidup berkelanjutan.</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Quick Links</h3>
                <ul>
                    <li><a href="#" class="hover:text-blue-300">Home</a></li>
                    <li><a href="#" class="hover:text-blue-300">Categories</a></li>
                    @guest('pembeli', 'organisasi')
                        <li><a href="{{ route('login') }}" class="hover:text-blue-300">Login</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-blue-300">Register</a></li>
                    @endguest
                    @auth('organisasi')
                        <li><a href="#" class="hover:text-blue-300">Profil</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="hover:text-blue-300">Logout</button>
                            </form>
                        </li>
                    @endauth
                    @auth('pembeli')
                        <li><a href="#" class="hover:text-blue-300">Profil</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="hover:text-blue-300">Logout</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Follow Us</h3>
                <ul>
                    <li><a href="#" class="hover:text-blue-300">Facebook</a></li>
                    <li><a href="#" class="hover:text-blue-300">Twitter</a></li>
                </ul>
            </div>
        </div>
        <div class="text-center mt-8">
            © 2025 ReuseMart. Semua Hak Dilindungi.
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