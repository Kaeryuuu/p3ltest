@extends('layouts.app')

@section('title', 'Owner Dashboard - ReUseMart')

@section('content')
<div class="flex h-screen bg-gray-100">
    <aside class="w-64 bg-white shadow-lg">
        <div class="p-4 border-b">
            <h2 class="text-xl font-semibold text-blue-600">ReUseMart - Owner</h2>
        </div>
        <nav class="mt-6">
            <a href="{{ route('owner.dashboard') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 {{ request()->routeIs('owner.dashboard') ? 'bg-gray-200' : '' }}">
                <span class="mr-2">🏠</span> Dashboard
            </a>
            <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200">
                <span class="mr-2">📄</span> Manage Nota
            </a>
            <a href="#laporanSubmenu" data-toggle="collapse" aria-expanded="false" class="flex items-center justify-between px-4 py-2 text-gray-600 hover:bg-gray-200">
                <div class="flex items-center">
                    <span class="mr-2">📊</span> Manage Laporan
                </div>
                <span>&#9662;</span>
            </a>
            <ul class="list-none collapse" id="laporanSubmenu">
                <li>
                    <a href="{{ route('owner.laporan.penjualan-kategori') }}" class="flex items-center px-8 py-2 text-sm text-gray-600 hover:bg-gray-200">Laporan Penjualan</a>
                </li>
                <li>
                    <a href="{{ route('owner.laporan.barang-expired') }}" class="flex items-center px-8 py-2 text-sm text-gray-600 hover:bg-gray-200">Laporan Barang Expired</a>
                </li>
            </ul>

            <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200">
                <span class="mr-2">⚙️</span> Settings
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-6 overflow-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Selamat Datang, {{ Auth::guard('pegawai')->user()->nama }}</h1>
            <div class="flex items-center space-x-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition duration-200">Logout</button>
                </form>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Dashboard Owner</h2>
            <p class="text-gray-600 mb-6">Pilih laporan yang ingin Anda lihat atau kelola dari menu di samping.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-medium text-blue-800">Laporan Penjualan per Kategori</h3>
                    <p class="text-blue-600 mb-4">Lihat rekapitulasi penjualan barang berdasarkan kategori dalam periode satu tahun.</p>
                    <a href="{{ route('owner.laporan.penjualan-kategori') }}" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">Lihat Laporan</a>
                </div>
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <h3 class="text-lg font-medium text-green-800">Laporan Barang Expired</h3>
                    <p class="text-green-600 mb-4">Lihat daftar barang yang masa penitipannya telah berakhir dan perlu tindak lanjut.</p>
                    <a href="{{ route('owner.laporan.barang-expired') }}" class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition duration-200">Lihat Laporan</a>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection