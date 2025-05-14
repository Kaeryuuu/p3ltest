@extends('layouts.app')

@section('title', 'Pembeli Dashboard - ReUseMart')

@section('content')
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <div class="p-4">
                <h2 class="text-xl font-semibold text-blue-600">Profile</h2>
            </div>
            <nav class="mt-6">
                <a href="{{ route('pembeli.dashboard') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200">
                    <span class="mr-2">🏠</span> Home
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200">
                    <span class="mr-2">⚙️</span> Settings
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>

                <a href="#" 
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200">
                    <span class="mr-2">🚪</span> Logout
                </a>

            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Profil</h1>
                <div class="flex items-center space-x-4">
                    <img src="public\images\product1.jpg" alt="User" class="rounded-full">
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
            <h2 class="text-2xl font-semibold text-gray-600 mb-4">Profil Pembeli</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p><strong>Nama:</strong> {{ $pembeli->nama }}</p>
                    <p><strong>Email:</strong> {{ $pembeli->email }}</p>
                </div>
                <div>
                    <p><strong>Telepon:</strong> {{ $pembeli->telepon }}</p>
                    <p><strong>Poin Loyalitas:</strong> {{ $pembeli->poin_loyalitas }}</p>
                </div>
            </div>
        </div>

            <!-- Transaction Table -->
            <div class="bg-white p-4 rounded-lg shadow">
                @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <h2 class="text-2xl font-semibold text-gray-600 mb-4">Histori Transaksi</h2>
                            <th class="p-2">ID Pembelian</th>
                            <th class="p-2">Tanggal Pembelian</th>
                            <th class="p-2">Total Harga</th>
                            <th class="p-2">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksis as $transaksi)
                            <tr class="border-b hover:bg-gray-100">
                                <td class="p-2">{{ $transaksi->id_pembelian }}</td>
                                <td class="p-2">{{ $transaksi->tanggal_pembelian }}</td>
                                <td class="p-2">Rp {{ number_format($transaksi->total, 2) }}</td>
                                <td class="p-2">
                                    <a href="{{ route('pembeli.transaction.detail', $transaksi->id_pembelian) }}" class="text-blue-600 hover:underline">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($transaksis->isEmpty())
                    <p class="text-gray-600 p-4">No transactions found.</p>
                @endif
            </div>
        </div>
    </div>
@endsection