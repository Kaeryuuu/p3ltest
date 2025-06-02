@extends('layouts.app')

@section('title', 'Pembeli Dashboard - ReUseMart')

@section('content')
<div class="flex min-h-screen bg-gradient-to-br from-slate-100 to-gray-200">
    <aside class="w-64 bg-white shadow-xl transition-transform duration-300 ease-in-out">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-center text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-700">
                ReUseMart
            </h2>
            <p class="text-xs text-center text-gray-500 mt-1">Profil Pembeli</p>
        </div>
        <nav class="mt-4">
            <a href="{{ route('pembeli.dashboard') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-150 {{ request()->routeIs('pembeli.dashboard') ? 'bg-blue-100 border-l-4 border-blue-600 text-blue-700 font-semibold' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            <a href="#" class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-150">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Pengaturan
            </a>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <a href="#" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
               class="flex items-center px-6 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-150">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Selamat Datang, {{ $pembeli->nama }}!</h1>
                <p class="text-gray-600">Lihat ringkasan profil dan transaksi Anda.</p>
            </div>
            <div class="flex items-center space-x-4">
                <img src="{{ $pembeli->foto_profil ? asset('storage/' . $pembeli->foto_profil) : asset('images/default-avatar.png') }}" 
                     alt="User Avatar" class="w-12 h-12 rounded-full object-cover border-2 border-blue-500 shadow-sm">
                </div>
        </header>

        <section class="bg-white p-6 rounded-xl shadow-lg mb-8 transition-shadow duration-300 hover:shadow-2xl">
            <h2 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-3">Informasi Profil</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <p><strong class="text-gray-600">Nama:</strong> {{ $pembeli->nama }}</p>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <p><strong class="text-gray-600">Email:</strong> {{ $pembeli->email }}</p>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.308 1.154a11.042 11.042 0 005.516 5.516l1.154-2.308a1 1 0 011.21-.502l4.493 1.498A1 1 0 0119.72 19h3.28a2 2 0 012 2v1a2 2 0 01-2 2h-1a19.79 19.79 0 01-18-18v-1a2 2 0 012-2zM15 9a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <p><strong class="text-gray-600">Telepon:</strong> {{ $pembeli->telepon ?? '-' }}</p>
                </div>
                <div class="flex items-center">
                     <svg class="w-5 h-5 mr-3 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    <p><strong class="text-gray-600">Poin Loyalitas:</strong> <span class="font-bold text-yellow-600">{{ $pembeli->poin_loyalitas ?? 0 }}</span></p>
                </div>
            </div>
        </section>

        <section class="bg-white p-6 rounded-xl shadow-lg transition-shadow duration-300 hover:shadow-2xl">
            <h2 class="text-2xl font-semibold text-gray-700 mb-6">Histori Transaksi</h2>
            
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                    <p class="font-bold">Sukses</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if ($transaksis->isEmpty())
                <div class="text-center py-10">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <p class="text-gray-500 text-lg">Anda belum memiliki transaksi.</p>
                    <a href="{{ route('homepage') }}" class="mt-4 inline-block px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        Mulai Belanja
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-4 text-sm font-semibold text-gray-600 tracking-wider">ID Pembelian</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 tracking-wider">Tanggal Pembelian</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 tracking-wider">Total Harga</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 tracking-wider text-center">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($transaksis as $transaksi)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="p-4 whitespace-nowrap text-sm text-gray-700">{{ $transaksi->id_pembelian }}</td>
                                    <td class="p-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($transaksi->tanggal_pembelian)->isoFormat('D MMMM YYYY') }}</td>
                                    <td class="p-4 whitespace-nowrap text-sm text-gray-800 font-medium">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                                    <td class="p-4 whitespace-nowrap text-sm text-center">
                                        <a href="{{ route('pembeli.transaction.detail', $transaksi->id_pembelian) }}" class="px-4 py-1.5 text-xs font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{-- Jika Anda menggunakan paginasi, tampilkan di sini --}}
                    {{-- {{ $transaksis->links() }} --}}
                </div>
            @endif
        </section>
    </main>
</div>
@endsection

@push('scripts')
<script>
// Script tambahan jika diperlukan
// Contoh: konfirmasi sebelum logout
// document.getElementById('logout-link').addEventListener('click', function(event) {
//     event.preventDefault();
//     if (confirm('Apakah Anda yakin ingin logout?')) {
//         document.getElementById('logout-form').submit();
//     }
// });
</script>
@endpush