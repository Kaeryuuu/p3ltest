
@extends('layouts.app')

@section('title', 'CS - Create Penitip')

@section('content')
    <div class="flex">
        <aside class="w-64 bg-white shadow-lg">
            <div class="p-4 border-b">
                <h2 class="text-xl font-semibold text-blue-600">ReUseMart - CS</h2>
            </div>
            <nav class="mt-6">
                <a href="{{ route('cs.dashboard') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 {{ request()->routeIs('cs.dashboard') ? 'bg-gray-200' : '' }}">
                    <span class="mr-2">🏠</span> Dashboard
                </a>
                <a href="{{ route('cs.penitip.index') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 {{ request()->routeIs('cs.penitip.index') ? 'bg-gray-200' : '' }}">
                    <span class="mr-2">👤</span> Manage Penitip
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200">
                    <span class="mr-2">⚙️</span> Settings
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200">
                    <span class="mr-2">❓</span> Help
                </a>
            </nav>
        </aside>

        <div class="flex-1 p-6">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Create New Penitip</h1>

            <div class="bg-white p-6 rounded-lg shadow max-w-4xl">
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form id="createPenitipForm" method="POST" action="{{ route('cs.penitip.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="no_ktp" class="block text-sm font-medium text-gray-700">KTP Number</label>
                        <input type="text" name="no_ktp" id="no_ktp" class="mt-1 p-2 border border-gray-300 rounded-lg w-full" value="{{ old('no_ktp') }}" required>
                    </div>
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="nama" id="nama" class="mt-1 p-2 border border-gray-300 rounded-lg w-full" value="{{ old('nama') }}" required>
                    </div>
                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="telepon" id="telepon" class="mt-1 p-2 border border-gray-300 rounded-lg w-full" value="{{ old('telepon') }}" required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" class="mt-1 p-2 border border-gray-300 rounded-lg w-full" value="{{ old('email') }}" required>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" class="mt-1 p-2 border border-gray-300 rounded-lg w-full" required>
                    </div>
                    <div>
                        <label for="foto_ktp" class="block text-sm font-medium text-gray-700">KTP Photo</label>
                        <input type="file" name="foto_ktp" id="foto_ktp" class="mt-1 p-2 border border-gray-300 rounded-lg w-full" accept="image/*" required>
                    </div>
                    <div class="md:col-span-2 flex justify-end space-x-4">
                        <a href="{{ route('cs.penitip.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Create Penitip</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div id="successResultModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Penitip Added Successfully</h2>
                <p class="text-gray-700 mb-4">{{ session('success.message') }}</p>
                <p class="text-gray-700 mb-4">
                    <strong>Name:</strong> {{ session('success.nama') }}<br>
                    <strong>Email:</strong> {{ session('success.email') }}<br>
                    <strong>Phone:</strong> {{ session('success.telepon') }}
                </p>
                <div class="flex justify-end space-x-4">
                    <button onclick="closeModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Add Another</button>
                    <a href="{{ route('cs.penitip.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">View All Penitips</a>
                </div>
            </div>
        </div>
    @endif

    <div id="createPenitipModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Pembuatan Penitip</h3>
            <p class="text-sm text-gray-600 mb-6">Apakah Anda yakin ingin membuat Penitip ini?</p>
            <div class="flex justify-end space-x-4">
                <button id="cancelCreatePenitip" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</button>
                <button id="confirmCreatePenitip" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Ya, Buat</button>
            </div>
        </div>
    </div>

    <script>
        function closeModal() {
            document.getElementById('successResultModal').remove();
            document.querySelector('form').reset();
        }

        const createPenitipForm = document.getElementById('createPenitipForm');
        const createPenitipModal = document.getElementById('createPenitipModal');
        const cancelCreatePenitip = document.getElementById('cancelCreatePenitip');
        const confirmCreatePenitip = document.getElementById('confirmCreatePenitip');

        createPenitipForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const noKtp = document.getElementById('no_ktp').value.trim();
            const nama = document.getElementById('nama').value.trim();
            const telepon = document.getElementById('telepon').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const fotoKtp = document.getElementById('foto_ktp').files[0];
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!noKtp) {
                showErrorModal('KTP number cannot be empty');
                return;
            }
            if (!nama) {
                showErrorModal('Name cannot be empty');
                return;
            }
            if (!telepon) {
                showErrorModal('Phone cannot be empty');
                return;
            }
            if (!email || !emailRegex.test(email)) {
                showErrorModal('Please enter a valid email');
                return;
            }
            if (!password) {
                showErrorModal('Password cannot be empty');
                return;
            }
            if (!fotoKtp) {
                showErrorModal('KTP photo is required');
                return;
            }
            createPenitipModal.classList.remove('hidden');
        });

        cancelCreatePenitip.addEventListener('click', function() {
            createPenitipModal.classList.add('hidden');
            showErrorModal('Penitip creation cancelled');
        });

        confirmCreatePenitip.addEventListener('click', function() {
            createPenitipModal.classList.add('hidden');
            createPenitipForm.submit();
            showSuccessModal('Penitip created successfully!');
        });

        @if ($errors->any())
            showErrorModal('Failed to create penitip. Please check your input.');
        @endif

        @if (session('success'))
            document.getElementById('successResultModal').classList.remove('hidden');
        @endif
    </script>
@endsection
