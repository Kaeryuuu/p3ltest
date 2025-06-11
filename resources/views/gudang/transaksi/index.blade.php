@extends('layouts.app')

@section('title', 'Daftar Transaksi Gudang')

@section('content')
    <div class="flex h-screen bg-gray-100">
        <aside class="w-64 bg-white shadow-lg">
            <div class="p-4 border-b">
                <h2 class="text-xl font-semibold text-blue-600">ReUseMart - Gudang</h2>
            </div>
            <nav class="mt-6">
                <a href="{{ route('gudang.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-md {{ request()->routeIs('gudang.dashboard') ? 'bg-gray-200 text-gray-900 font-semibold' : '' }}">
                    <span class="mr-3 text-lg">🏠</span> Dashboard
                </a>
                <a href="{{ route('gudang.barang-titipan.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-md {{ request()->routeIs('gudang.barang-titipan.index') ? 'bg-gray-200 text-gray-900 font-semibold' : '' }}">
                    <span class="mr-3 text-lg">📦</span> Manage Barang Titipan
                </a>
                <a href="{{ route('gudang.transaksi.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-md {{ request()->routeIs('gudang.transaksi.index') ? 'bg-gray-200 text-gray-900 font-semibold' : '' }}">
                    <span class="mr-3 text-lg">🚚</span> Manage Transaksi
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-md">
                    <span class="mr-3 text-lg">⚙️</span> Settings
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-md">
                    <span class="mr-3 text-lg">❓</span> Help
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-red-100 hover:text-red-700 rounded-md">
                        <span class="mr-3 text-lg">🚪</span> Logout
                    </button>
                </form>
            </nav>
        </aside>

        <main class="flex-1 p-8 overflow-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Daftar Transaksi</h1>
                <div class="flex items-center space-x-3">
                    <span class="text-sm text-gray-500">Last updated: {{ now()->format('H:i, d F Y') }}</span>
                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-semibold shadow-md">
                        @auth('pegawai')
                            {{ strtoupper(substr(Auth::guard('pegawai')->user()->nama ?? 'GU', 0, 2)) }}
                        @else
                            GU
                        @endauth
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow" role="alert">
                    <p class="font-bold">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white p-6 rounded-xl shadow-xl">
                <h2 class="text-xl font-semibold text-gray-700 mb-6">Transaksi Terbaru</h2>
                @if ($transaksi->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Belum ada transaksi</h3>
                        <p class="mt-1 text-sm text-gray-500">Saat ini belum ada transaksi yang perlu diproses.</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach ($transaksi as $item)
                            <div class="border border-gray-200 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 ease-in-out">
                                <div class="flex flex-col md:flex-row justify-between md:items-start">
                                    <div class="flex-1 mb-4 md:mb-0">
                                        <div class="flex items-center mb-2">
                                            <h3 class="text-xl font-semibold text-blue-600 mr-3">Transaksi #{{ $item->id_pembelian }}</h3>
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                                @if($item->status == 'sedang dikemas') bg-yellow-100 text-yellow-800
                                                @elseif($item->status == 'akan diambil') bg-blue-100 text-blue-800
                                                @elseif($item->status == 'siap diambil') bg-cyan-100 text-cyan-800
                                                @elseif($item->status == 'sedang dikirim') bg-indigo-100 text-indigo-800
                                                @elseif($item->status == 'sudah diambil') bg-purple-100 text-purple-800
                                                @elseif($item->status == 'transaksi selesai') bg-green-100 text-green-800
                                                @elseif($item->status == 'hangus') bg-red-100 text-red-800
                                                @elseif($item->status == 'dibatalkan') bg-pink-100 text-pink-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ Str::title(str_replace('_', ' ', $item->status)) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-700 mb-1"><strong class="font-medium">Pembeli:</strong> {{ $item->pembeli->nama ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-700"><strong class="font-medium">Tanggal:</strong> {{ \Carbon\Carbon::parse($item->tanggal_pembelian)->translatedFormat('d M Y') }}</p>
                                        <p class="text-sm text-gray-700"><strong class="font-medium">Total Akhir:</strong> Rp {{ number_format($item->total_akhir, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-700 mt-1"><strong class="font-medium">Barang:</strong>
                                            @if($item->barangTitipan->isNotEmpty())
                                                {{ $item->barangTitipan->first()->nama ?? 'Nama Barang Tidak Tersedia' }}
                                                @if($item->barangTitipan->count() > 1)
                                                    <span class="text-xs text-gray-500">(+{{ $item->barangTitipan->count() - 1 }} lainnya)</span>
                                                @endif
                                            @else
                                                <span class="text-gray-500 italic">Tidak ada barang</span>
                                            @endif
                                        </p>
                                    </div>
                                    @if ($item->barangTitipan->isNotEmpty() && $item->barangTitipan->first()->fotos->isNotEmpty())
                                        <div class="flex space-x-2 self-start md:self-center mt-3 md:mt-0">
                                            @foreach ($item->barangTitipan->first()->fotos->take(2) as $foto)
                                                <img src="{{ Storage::url($foto->url_foto) }}" alt="Foto Barang" class="w-20 h-20 object-cover rounded-md border border-gray-300">
                                            @endforeach
                                            @if($item->barangTitipan->first()->fotos->count() > 2)
                                            <div class="w-20 h-20 rounded-md border border-gray-300 bg-gray-100 flex items-center justify-center text-xs text-gray-500">
                                                +{{ $item->barangTitipan->first()->fotos->count() - 2 }}
                                            </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="w-20 h-20 bg-gray-200 rounded-md flex items-center justify-center text-gray-400 mt-3 md:mt-0 self-start md:self-center">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-end">
                                    <a href="{{ route('gudang.transaksi.detail', $item->id_pembelian) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Lihat Detail
                                        <svg class="ml-2 -mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8">
                        {{-- Add pagination if $transaksi is paginated: $transaksi->links() --}}
                    </div>
                @endif
            </div>
        </main>
    </div>
@endsection