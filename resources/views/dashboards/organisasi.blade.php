@extends('layouts.app')

@section('title', 'Organisasi Dashboard - ReUseMart')

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
                <h1 class="text-2xl font-semibold text-gray-800">Selamat Datang, {{ Auth::guard('organisasi')->user()->nama }}</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Last updated: {{ now()->format('H:i A, d M Y') }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition duration-200">Logout</button>
                    </form>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Dashboard Organisasi</h2>
                <p class="text-gray-600 mb-6">Welcome to your organization dashboard. Manage your donation requests or explore other features.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-blue-800">Manage Donation Requests</h3>
                        <p class="text-blue-600 mb-4">Create, update, or view your donation requests to support your initiatives.</p>
                        <a href="{{ route('organisasi.request-donasi.index') }}" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">Go to Donation Requests</a>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-800">Quick Stats</h3>
                        <p class="text-gray-600">Total Donation Requests: <span class="font-semibold">{{ \App\Models\RequestDonasi::where('id_organisasi', Auth::guard('organisasi')->user()->id_organisasi)->count() }}</span></p>
                        <p class="text-gray-600">Pending Requests: <span class="font-semibold">{{ \App\Models\RequestDonasi::where('id_organisasi', Auth::guard('organisasi')->user()->id_organisasi)->where('status', 'Pending')->count() }}</span></p>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection