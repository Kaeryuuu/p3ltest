@extends('layouts.app')

@section('title', 'Organisasi - Create Donation Request')

@section('content')
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg">
            <div class="p-4 border-b">
                <h2 class="text-xl font-semibold text-blue-600">ReUseMart - Organisasi</h2>
            </div>
            <nav class="mt-6">
                <a href="{{ route('organisasi.dashboard') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 {{ request()->routeIs('organisasi.dashboard') ? 'bg-gray-200' : '' }}">
                    <span class="mr-2">🏠</span> Dashboard
                </a>
                <a href="{{ route('organisasi.request-donasi.index') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 {{ request()->routeIs('organisasi.request-donasi.index') ? 'bg-gray-200' : '' }}">
                    <span class="mr-2">📦</span> Manage Donation Requests
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
                <h1 class="text-2xl font-semibold text-gray-800">Create New Donation Request</h1>
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

                <form method="POST" action="{{ route('organisasi.request-donasi.store') }}" class="grid grid-cols-1 gap-6">
                    @csrf
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="deskripsi" id="deskripsi" class="mt-1 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" rows="4" required>{{ old('deskripsi') }}</textarea>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('organisasi.request-donasi.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancel</a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">Create Donation Request</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection