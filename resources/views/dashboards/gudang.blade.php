@extends('layouts.app')

@section('title', 'Gudang Dashboard')

@section('content')
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg">
            <div class="p-4 border-b">
                <h2 class="text-xl font-semibold text-blue-600">ReUseMart - Gudang</h2>
            </div>
            <nav class="mt-6">
                <a href="{{ route('gudang.dashboard') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 {{ request()->routeIs('gudang.dashboard') ? 'bg-gray-200' : '' }}">
                    <span class="mr-2">🏠</span> Dashboard
                </a>
                <a href="{{ route('gudang.barang-titipan.index') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 {{ request()->routeIs('gudang.barang-titipan.index') ? 'bg-gray-200' : '' }}">
                    <span class="mr-2">👤</span> Manage BarangTitipan
                </a>
                <a href="{{ route('gudang.transaksi.index') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 {{ request()->routeIs('gudang.transaksi.index') ? 'bg-gray-200' : '' }}">
                    <span class="mr-2">📦</span> Manage Transaksi
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200">
                    <span class="mr-2">⚙️</span> Settings
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200">
                    <span class="mr-2">❓</span> Help
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 w-full text-left">
                        <span class="mr-2">🚪</span> Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Gudang Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Last updated: {{ now()->format('H:i A, d M Y') }}</span>
                    <img src="https://via.placeholder.com/40" alt="User" class="rounded-full">
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-medium text-gray-700 mb-4">Overview</h2>
                <p class="text-gray-600">Welcome to the Gudang Dashboard. Use the sidebar to manage Barang Tipan or other tasks.</p>
            </div>
        </main>
    </div>
@endsection