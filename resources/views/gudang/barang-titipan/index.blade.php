@extends('layouts.app')

@section('title', 'Manage Barang Titipan')

@section('content')
    <div class="flex h-screen bg-gray-100">
        <aside class="w-64 bg-white shadow-lg">
            <div class="p-4 border-b">
                <h2 class="text-xl font-semibold text-blue-600">ReUseMart - Gudang</h2>
            </div>
            <nav class="mt-6">
                <a href="{{ route('gudang.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-md {{ request()->routeIs('gudang.dashboard') ? 'bg-gray-200 text-gray-900 font-semibold' : '' }}">
                    <span class="mr-3 text-lg">🏠</span> Dashboard
                </a>
                <a href="{{ route('gudang.barang-titipan.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-md {{ request()->routeIs('gudang.barang-titipan.index') ? 'bg-gray-200 text-gray-900 font-semibold' : '' }}">
                    <span class="mr-3 text-lg">📦</span> Manage Barang Titipan
                </a>
                <a href="{{ route('gudang.transaksi.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-md {{ request()->routeIs('gudang.transaksi.index') ? 'bg-gray-200 text-gray-900 font-semibold' : '' }}">
                    <span class="mr-3 text-lg">🚚</span> Manage Transaksi
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-md">
                    <span class="mr-3 text-lg">⚙️</span> Settings
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-md">
                    <span class="mr-3 text-lg">❓</span> Help
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-3 text-gray-700 hover:bg-red-100 hover:text-red-700 rounded-md">
                        <span class="mr-3 text-lg">🚪</span> Logout
                    </button>
                </form>
            </nav>
        </aside>

        <main class="flex-1 p-8 overflow-auto">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Daftar Barang Titipan</h1>
                <div class="flex items-center space-x-3">
                    <span class="text-sm text-gray-500">Last updated: {{ now()->format('H:i, d M Y') }}</span>
                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-semibold shadow-md">
                        {{ strtoupper(substr(Auth::user()->name ?? 'GU', 0, 2)) }}
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow" role="alert">
                    <p class="font-bold">Sukses</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

             <div class="bg-white p-6 rounded-xl shadow-xl">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-700">Semua Barang Titipan</h2>
                    {{-- Tambah tombol "Tambah Barang" jika ada fitur tersebut --}}
                    {{-- <a href="{{ route('gudang.barang-titipan.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-semibold text-sm">
                        + Tambah Barang
                    </a> --}}
                </div>

                @if ($barangTitipan->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Belum ada barang titipan</h3>
                        <p class="mt-1 text-sm text-gray-500">Saat ini tidak ada barang titipan yang terdaftar.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach ($barangTitipan as $barang)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 ease-in-out flex flex-col">
                                {{-- GAMBAR UTAMA KARTU --}}
                                @if ($barang->fotos && $barang->fotos->count() > 0)
                                    {{-- Mengambil foto pertama dari relasi 'fotos' --}}
                                    <img src="{{ Storage::url($barang->fotos->first()->url_foto) }}" alt="Foto {{ $barang->nama }}" class="w-full h-48 object-cover rounded-t-lg">
                                @else
                                    {{-- Placeholder jika tidak ada foto --}}
                                    <div class="w-full h-48 bg-gray-200 rounded-t-lg flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif

                                <div class="p-5 flex flex-col flex-grow">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1 truncate" title="{{ $barang->nama }}">{{ $barang->nama }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">Kode: <span class="font-medium text-gray-700">{{ $barang->kode_barang }}</span></p>

                                    {{-- ... (Status, Harga, Berat, Kondisi, dll. tetap sama) ... --}}
                                    <div class="mb-3">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            @if($barang->status == 'tersedia') bg-green-100 text-green-800
                                            @elseif($barang->status == 'terjual') bg-blue-100 text-blue-800
                                            @elseif($barang->status == 'akan diambil') bg-yellow-100 text-yellow-800
                                            @elseif($barang->status == 'diambil') bg-purple-100 text-purple-800
                                            @elseif($barang->status == 'kadaluarsa') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ Str::title(str_replace('_', ' ', $barang->status)) }}
                                        </span>
                                    </div>

                                    <div class="text-sm space-y-1 text-gray-600 mb-4">
                                        <p><strong class="font-medium">Harga:</strong> Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                                        <p><strong class="font-medium">Berat:</strong> {{ $barang->berat }} kg</p>
                                        <p><strong class="font-medium">Kondisi:</strong> {{ Str::title($barang->kondisi) }}</p>
                                        <p><strong class="font-medium">Kadaluarsa:</strong> {{ $barang->tanggal_kadaluarsa ? \Carbon\Carbon::parse($barang->tanggal_kadaluarsa)->translatedFormat('d M Y') : '-' }}</p>
                                        @if ($barang->perpanjangan)
                                            <p><strong class="font-medium">Perpanjangan:</strong> <span class="text-green-600">Aktif</span></p>
                                        @endif
                                        @if ($barang->garansi)
                                            <p><strong class="font-medium">Garansi Hingga:</strong> {{ \Carbon\Carbon::parse($barang->garansi)->translatedFormat('d M Y') }}</p>
                                        @endif
                                    </div>


                                    {{-- THUMBNAIL FOTO LAINNYA --}}
                                    @if ($barang->fotos && $barang->fotos->count() > 1)
                                        <div class="mt-2 mb-4">
                                            <p class="text-xs text-gray-500 mb-1">Foto lainnya:</p>
                                            <div class="flex space-x-2">
                                                {{-- Mengambil 2 foto berikutnya setelah foto pertama --}}
                                                @foreach ($barang->fotos->slice(1)->take(2) as $fotoObjek)
                                                    <img src="{{ Storage::url($fotoObjek->url_foto) }}" alt="Foto {{ $barang->nama }}" class="w-12 h-12 object-cover rounded border border-gray-300">
                                                @endforeach
                                                @if($barang->fotos->count() > 3) {{-- Jika total foto lebih dari 1 (cover) + 2 (thumbnail) --}}
                                                <div class="w-12 h-12 rounded border border-gray-300 bg-gray-100 flex items-center justify-center text-xs text-gray-500">
                                                    +{{ $barang->fotos->count() - 3 }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="mt-auto pt-4 border-t border-gray-200 p-5">
                                        {{-- Tombol "Lihat Detail" akan selalu ada dan mengarah ke halaman detail --}}
                                        <a href="{{ route('gudang.barang-titipan.detail', $barang->kode_barang) }}"
                                        class="w-full block text-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            Lihat Detail
                                            @if ($barang->status == 'akan diambil')
                                            / Catat Pengambilan
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                     <div class="mt-8">
                        {{-- Pastikan variabel $barangTitipan adalah instance Paginator --}}
                        {{-- {{ $barangTitipan->links() }} --}}
                    </div>
                @endif
            </div>
        </main>
    </div>
@endsection