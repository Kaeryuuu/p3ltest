<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function switchForm(formType) {
            document.getElementById('user-form').classList.add('hidden');
            document.getElementById('organization-form').classList.add('hidden');
            document.getElementById(formType + '-form').classList.remove('hidden');

            document.getElementById('user-tab').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('organization-tab').classList.remove('bg-blue-600', 'text-white');

            document.getElementById(formType + '-tab').classList.add('bg-blue-600', 'text-white');
        }
    </script>
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="bg-white p-10 rounded-lg shadow-lg w-full max-w-lg">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Buat Akun</h2>

        <!-- Tabs -->
        <div class="flex mb-6">
            <button id="user-tab" onclick="switchForm('user')" class="flex-1 py-2 border border-blue-600 text-blue-600 rounded-l-lg">User</button>
            <button id="organization-tab" onclick="switchForm('organization')" class="flex-1 py-2 border border-blue-600 text-blue-600 rounded-r-lg">Organisasi</button>
        </div>

        <!-- User Form -->
        <form id="user-form" method="POST" action="{{ route('registerUser') }}">
            @csrf
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-600">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="w-full p-3 mt-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-0" value="{{ old('nama') }}" required autofocus>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                <input type="email" id="email" name="email" class="w-full p-3 mt-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-0" value="{{ old('email') }}" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
                <input type="password" id="password" name="password" class="w-full p-3 mt-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-0" required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-600">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-3 mt-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-0" required>
            </div>

            <div class="mb-6">
                <label for="telepon" class="block text-sm font-medium text-gray-600">Telepon</label>
                <input type="text" id="telepon" name="telepon" class="w-full p-3 mt-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-0" value="{{ old('telepon') }}" required>
            </div>

            <button type="submit" class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">Register sebagai Pembeli</button>
        </form>

        <!-- Organization Form -->
        <form id="organization-form" method="POST" action="{{ route('registerORG') }}" class="hidden">
            @csrf
            <div class="mb-4">
                <label for="org_name" class="block text-sm font-medium text-gray-600">Nama Organisasi</label>
                <input type="text" id="nama" name="nama" class="w-full p-3 mt-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-0" value="{{ old('org_name') }}" required autofocus>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-600">Deskripsi Organisasi</label>
                <textarea id="description" name="deskripsi" rows="4" class="w-full p-3 mt-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-0" required>{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="email_org" class="block text-sm font-medium text-gray-600">Email Organisasi</label>
                <input type="email" id="email_org" name="email" class="w-full p-3 mt-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 outline-0" value="{{ old('email') }}" required>
            </div>

            <div class="mb-4">
                <label for="password_org" class="block text-sm font-medium text-gray-600">Password</label>
                <input type="password" id="password_org" name="password" class="w-full p-3 mt-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-0" required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation_org" class="block text-sm font-medium text-gray-600">Konfirmasi Password</label>
                <input type="password" id="password_confirmation_org" name="password_confirmation" class="w-full p-3 mt-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-0" required>
            </div>

            <button type="submit" class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">Register sebagai Organisasi</button>
        </form>

        <div class="text-center mt-4">
            <small class="text-gray-600">Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login disini</a></small>
        </div>
    </div>

    <script>
        // Set default active tab to User
        switchForm('user');
    </script>

</body>

</html>