@extends('layouts.app')

@section('title', 'CS - Create Penitip')

@section('content')
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
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

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Create New Penitip</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Last updated: {{ now()->format('H:i A, d M Y') }}</span>
                    <img src="https://via.placeholder.com/40" alt="User" class="rounded-full">
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white p-6 rounded-lg shadow max-w-4xl mx-auto">
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('cs.penitip.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="no_ktp" class="block text-sm font-medium text-gray-700">KTP Number</label>
                        <input type="text" name="no_ktp" id="no_ktp" class="mt-1 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" value="{{ old('no_ktp') }}" required>
                    </div>
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="nama" id="nama" class="mt-1 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" value="{{ old('nama') }}" required>
                    </div>
                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="telepon" id="telepon" class="mt-1 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" value="{{ old('telepon') }}" required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" class="mt-1 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" value="{{ old('email') }}" required>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" class="mt-1 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" required>
                    </div>
                    <div>
                        <label for="foto_ktp" class="block text-sm font-medium text-gray-700">KTP Photo</label>
                        <input type="file" name="foto_ktp" id="foto_ktp" class="mt-1 p-2 border border-gray-300 rounded-lg w-full" accept="image/*" required>
                    </div>
                    <div class="md:col-span-2 flex justify-end space-x-4">
                        <a href="{{ route('cs.penitip.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancel</a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">Create Penitip</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection