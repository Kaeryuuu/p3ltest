@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Buat Akun</h2>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Tabs -->
            <div class="flex justify-center mb-6">
                <button class="px-4 py-2 font-medium text-gray-600 hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 user-tab active" data-tab="user">User</button>
                <button class="px-4 py-2 font-medium text-gray-600 hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 user-tab" data-tab="organisasi">Organisasi</button>
            </div>

            <!-- User Registration Form -->
            <div class="tab-content" id="user">
                <form id="registerPembeliForm" method="POST" action="{{ route('registerUser') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" class="mt-1 p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('nama') }}" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" class="mt-1 p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-4">
                        <label for="telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
                        <input type="text" name="telepon" id="telepon" class="mt-1 p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('telepon') }}" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" class="mt-1 p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg w-full hover:bg-blue-700 transition duration-200">Register sebagai Pembeli</button>
                </form>
            </div>

            <!-- Organisasi Registration Form -->
            <div class="tab-content hidden" id="organisasi">
                <form id="registerOrganisasiForm" method="POST" action="{{ route('registerORG') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="nama_org" class="block text-sm font-medium text-gray-700">Nama Organisasi</label>
                        <input type="text" name="nama" id="nama_org" class="mt-1 p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('nama') }}" required>
                    </div>
                    <div class="mb-4">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi Organisasi</label>
                        <textarea name="deskripsi" id="deskripsi" class="mt-1 p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4">{{ old('deskripsi') }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label for="email_org" class="block text-sm font-medium text-gray-700">Email Organisasi</label>
                        <input type="email" name="email" id="email_org" class="mt-1 p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-4">
                        <label for="telepon_org" class="block text-sm font-medium text-gray-700">Telepon</label>
                        <input type="text" name="telepon" id="telepon_org" class="mt-1 p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('telepon') }}" required>
                    </div>
                    <div class="mb-4">
                        <label for="password_org" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password_org" class="mt-1 p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-6">
                        <label for="password_confirmation_org" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation_org" class="mt-1 p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg w-full hover:bg-blue-700 transition duration-200">Register sebagai Organisasi</button>
                </form>
            </div>

            <p class="mt-6 text-center text-sm text-gray-600">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login disini</a>
            </p>
        </div>
    </div>

    <!-- Pembeli Confirmation Modal -->
    <div id="pembeliModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Registrasi</h3>
            <p class="text-sm text-gray-600 mb-6">Apakah Anda yakin ingin mendaftar sebagai Pembeli?</p>
            <div class="flex justify-end space-x-4">
                <button id="cancelPembeli" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</button>
                <button id="confirmPembeli" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Ya, Daftar</button>
            </div>
        </div>
    </div>

    <!-- Organisasi Confirmation Modal -->
    <div id="organisasiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Registrasi</h3>
            <p class="text-sm text-gray-600 mb-6">Apakah Anda yakin ingin mendaftar sebagai Organisasi?</p>
            <div class="flex justify-end space-x-4">
                <button id="cancelOrganisasi" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</button>
                <button id="confirmOrganisasi" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Ya, Daftar</button>
            </div>
        </div>
    </div>

    <script>
        // Tab switching
        document.querySelectorAll('.user-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.user-tab').forEach(t => {
                    t.classList.remove('active', 'border-blue-600', 'text-blue-600');
                    t.classList.add('text-gray-600', 'border-transparent');
                });
                this.classList.add('active', 'border-blue-600', 'text-blue-600');
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });
                document.getElementById(this.dataset.tab).classList.remove('hidden');
            });
        });

        // Pembeli Modal Handling
        const pembeliForm = document.getElementById('registerPembeliForm');
        const pembeliModal = document.getElementById('pembeliModal');
        const cancelPembeli = document.getElementById('cancelPembeli');
        const confirmPembeli = document.getElementById('confirmPembeli');

        pembeliForm.addEventListener('submit', function(event) {
            event.preventDefault();
            pembeliModal.classList.remove('hidden');
        });

        cancelPembeli.addEventListener('click', function() {
            pembeliModal.classList.add('hidden');
        });

        confirmPembeli.addEventListener('click', function() {
            pembeliModal.classList.add('hidden');
            pembeliForm.submit();
        });

        // Organisasi Modal Handling
        const organisasiForm = document.getElementById('registerOrganisasiForm');
        const organisasiModal = document.getElementById('organisasiModal');
        const cancelOrganisasi = document.getElementById('cancelOrganisasi');
        const confirmOrganisasi = document.getElementById('confirmOrganisasi');

        organisasiForm.addEventListener('submit', function(event) {
            event.preventDefault();
            organisasiModal.classList.remove('hidden');
        });

        cancelOrganisasi.addEventListener('click', function() {
            organisasiModal.classList.add('hidden');
        });

        confirmOrganisasi.addEventListener('click', function() {
            organisasiModal.classList.add('hidden');
            organisasiForm.submit();
        });
    </script>
@endsection