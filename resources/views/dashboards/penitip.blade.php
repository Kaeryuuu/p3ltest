@extends('layouts.app')

@section('title', 'Penitip Dashboard')

@section('content')
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg">
            <div class="p-4 border-b">
                <h2 class="text-xl font-semibold text-blue-600">ReUseMart - Penitip</h2>
            </div>
            <nav class="mt-6">
                <a href="{{ route('penitip.dashboard') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 {{ request()->routeIs('penitip.dashboard') ? 'bg-gray-200' : '' }}">
                    <span class="mr-2">🏠</span> Dashboard
                </a>
                <a href="{{ route('penitip.barang-titipan.manage') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 {{ request()->routeIs('penitip.barang-titipan.manage') ? 'bg-gray-200' : '' }}">
                    <span class="mr-2">📦</span> Manage Barang Titipan
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
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Penitip Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Last updated: {{ now()->format('H:i A, d M Y') }}</span>
                    <img src="{{ Auth::guard('penitip')->user()->url_foto ?? 'https://via.placeholder.com/40' }}" alt="User" class="rounded-full w-10 h-10">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Logout</button>
                    </form>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-medium text-gray-700 mb-4">Overview</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-blue-100 p-4 rounded-lg">
                        <p class="text-sm text-blue-600">Poin Loyalitas</p>
                        <p class="text-lg font-semibold">{{ Auth::guard('penitip')->user()->poin_loyalitas ?? 0 }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg">
                        <p class="text-sm text-green-600">Saldo</p>
                        <p class="text-lg font-semibold">{{ number_format(Auth::guard('penitip')->user()->saldo ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-lg">
                        <p class="text-sm text-yellow-600">Rating</p>
                        <p class="text-lg font-semibold">{{ Auth::guard('penitip')->user()->rating > 0 ? number_format(Auth::guard('penitip')->user()->rating, 1) . ' / 5' : '-' }}</p>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-lg">
                        <p class="غم text-blue-600">Badge</p>
                        <p class="text-lg font-semibold">{{ Auth::guard('penitip')->user()->badge ?? '-' }}</p>
                    </div>
                </div>
                <p class="text-gray-600 mt-4">Welcome, {{ Auth::guard('penitip')->user()->nama ?? 'Penitip' }}. Use the sidebar to manage your consigned items or view details.</p>
            </div>
        </main>
    </div>
@endsection