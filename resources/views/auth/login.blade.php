<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ReUseMart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/heroicons/2.0.16/24/outline/envelope.svg" rel="preload" as="image"> <link href="https://cdnjs.cloudflare.com/ajax/libs/heroicons/2.0.16/24/outline/lock-closed.svg" rel="preload" as="image">
    <style>
        /* Opsi: Tambahkan font kustom jika diinginkan */
        /* @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        } */
    </style>
</head>
<body class="bg-gradient-to-br from-blue-100 via-indigo-50 to-gray-100 h-screen flex items-center justify-center p-6">
    <div class="bg-white p-8 sm:p-10 rounded-xl shadow-2xl w-full max-w-lg transform transition-all duration-500 hover:scale-105">
        <div class="flex justify-center mb-8">
            <img src="https://via.placeholder.com/150x50?text=ReUseMart" alt="ReUseMart Logo" class="h-12">
            </div>

        <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Selamat Datang!</h2>
        <p class="text-center text-gray-500 mb-8">Login untuk melanjutkan ke ReUseMart.</p>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <strong class="font-bold">Oops! Terjadi kesalahan:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <strong class="font-bold">Sukses!</strong>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <input type="email" id="email" name="email" class="w-full p-3 pl-10 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors duration-300" value="{{ old('email') }}" required autofocus placeholder="anda@email.com">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <div class="relative">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <input type="password" id="password" name="password" class="w-full p-3 pl-10 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors duration-300" required placeholder="••••••••">
                </div>
            </div>

 

            <button type="submit" class="w-full py-3 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors duration-300">
                Login
            </button>
        </form>

        <div class="text-center mt-8">
            <p class="text-sm text-gray-600">Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:underline hover:text-blue-700">Register disini</a></p>
        </div>
    </div>
</body>
</html>