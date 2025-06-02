@extends('layouts.app')

@section('title', 'Detail Barang Titipan: ' . $barang->nama)

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
                <a href="{{ route('gudang.barang-titipan.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-md {{ request()->routeIs('gudang.barang-titipan.index') || request()->routeIs('gudang.barang-titipan.detail') ? 'bg-gray-200 text-gray-900 font-semibold' : '' }}">
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
            <div class="mb-6">
                <a href="{{ route('gudang.barang-titipan.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 hover:underline">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                    Kembali ke Daftar Barang Titipan
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow" role="alert">
                    <p class="font-bold">Sukses</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white p-6 md:p-8 rounded-xl shadow-xl">
                <div class="flex flex-col md:flex-row justify-between md:items-start mb-6 pb-6 border-b border-gray-200">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-1">{{ $barang->nama }}</h1>
                        <p class="text-sm text-gray-500">Kode Barang: {{ $barang->kode_barang }}</p>
                    </div>
                    <div class="mt-3 md:mt-0">
                        <span class="px-4 py-2 text-sm font-semibold rounded-full
                            @if($barang->status == 'tersedia') bg-green-100 text-green-800
                            @elseif($barang->status == 'terjual') bg-blue-100 text-blue-800
                            @elseif($barang->status == 'akan diambil') bg-yellow-100 text-yellow-800
                            @elseif($barang->status == 'sudah diambil') bg-purple-100 text-purple-800
                            @elseif($barang->status == 'kadaluarsa') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            Status: {{ Str::title(str_replace('_', ' ', $barang->status)) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-6">
                        <section>
                            <h2 class="text-xl font-semibold text-gray-700 mb-3">Informasi Barang</h2>
                            <div class="text-sm space-y-2">
                                <div class="flex items-start">
                                    <span class="text-gray-600 w-48 text-left">Deskripsi</span>
                                    <span class="text-gray-600 pr-1">:</span>
                                    <span class="text-gray-800 break-words">{{ $barang->deskripsi ?: '-' }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-600 w-48 text-left">Harga</span>
                                    <span class="text-gray-600 pr-1">:</span>
                                    <span class="text-gray-800">Rp {{ number_format($barang->harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-600 w-48 text-left">Berat</span>
                                    <span class="text-gray-600 pr-1">:</span>
                                    <span class="text-gray-800">{{ $barang->berat }} kg</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-600 w-48 text-left">Kondisi</span>
                                    <span class="text-gray-600 pr-1">:</span>
                                    <span class="text-gray-800">{{ Str::title($barang->kondisi) }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-600 w-48 text-left">Kategori</span>
                                    <span class="text-gray-600 pr-1">:</span>
                                    <span class="text-gray-800">{{ $barang->subkategori->namaSubKategori ?? ($barang->kategori->nama_kategori ?? '-') }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-600 w-48 text-left">Tanggal Kadaluarsa</span>
                                    <span class="text-gray-600 pr-1">:</span>
                                    <span class="text-gray-800">{{ $barang->tanggal_kadaluarsa ? \Carbon\Carbon::parse($barang->tanggal_kadaluarsa)->translatedFormat('d F Y') : '-' }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-600 w-48 text-left">Perpanjangan</span>
                                    <span class="text-gray-600 pr-1">:</span>
                                    <span class="text-gray-800">{{ $barang->perpanjangan ? 'Ya' : 'Tidak' }}</span>
                                </div>
                                @if($barang->perpanjangan)
                                <div class="flex items-start">
                                    <span class="text-gray-600 w-48 text-left">Tanggal Akhir Perpanjangan</span>
                                    <span class="text-gray-600 pr-1">:</span>
                                    <span class="text-gray-800">{{ $barang->tanggal_akhir_perpanjangan ? \Carbon\Carbon::parse($barang->tanggal_akhir_perpanjangan)->translatedFormat('d F Y') : '-' }}</span>
                                </div>
                                @endif
                                <div class="flex items-start">
                                    <span class="text-gray-600 w-48 text-left">Garansi</span>
                                    <span class="text-gray-600 pr-1">:</span>
                                    <span class="text-gray-800">{{ $barang->garansi ? 'Ya, hingga ' . \Carbon\Carbon::parse($barang->garansi)->translatedFormat('d F Y') : 'Tidak Ada' }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-600 w-48 text-left">Tanggal Penitipan</span>
                                    <span class="text-gray-600 pr-1">:</span>
                                    <span class="text-gray-800">{{ $barang->transaksiPenitipan && $barang->transaksiPenitipan->tanggal_penitipan ? \Carbon\Carbon::parse($barang->transaksiPenitipan->tanggal_penitipan)->translatedFormat('d F Y') : '-' }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-600 w-48 text-left">Dititipkan Oleh</span>
                                    <span class="text-gray-600 pr-1">:</span>
                                    <span class="text-gray-800 break-words">{{ $barang->penitip->nama ?? '-' }} ({{ $barang->penitip->email ?? '-' }})</span>
                                </div>
                            </div>
                        </section>
                        @if ($barang->fotos && $barang->fotos->count() > 0)
                        <section class="mt-6">
                            <h2 class="text-xl font-semibold text-gray-700 mb-3">Foto Barang</h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach ($barang->fotos as $foto)
                                    <a href="{{ Storage::url($foto->url_foto) }}" data-fancybox="gallery" data-caption="Foto {{ $barang->nama }}">
                                        <img src="{{ Storage::url($foto->url_foto) }}" alt="Foto {{ $barang->nama }}" class="w-full h-32 object-cover rounded-lg border border-gray-300 shadow-sm hover:shadow-md transition-shadow">
                                    </a>
                                @endforeach
                            </div>
                        </section>
                        @else
                        <section class="mt-6">
                            <h2 class="text-xl font-semibold text-gray-700 mb-3">Foto Barang</h2>
                            <p class="text-gray-500">Tidak ada foto untuk barang ini.</p>
                        </section>
                        @endif
                    </div>

                    <div class="lg:col-span-1 space-y-6">
                        @if ($barang->status == 'akan diambil')
                        <section class="bg-blue-50 p-6 rounded-lg shadow-md border border-blue-200">
                            <h3 class="text-xl font-semibold text-blue-700 mb-4">Konfirmasi Pengambilan oleh Penitip</h3>
                            <form action="{{ route('gudang.barang-titipan.record-pickup', $barang->kode_barang) }}" method="POST">
                                @csrf
                                <p class="text-sm text-gray-700 mb-1">Pastikan barang ini benar-benar telah diambil oleh penitip/pemiliknya.</p>
                                <p class="text-xs text-gray-500 mb-4">Tindakan ini akan mengubah status barang menjadi "Sudah Diambil".</p>
                                <div class="mt-6">
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Konfirmasi Telah Diambil
                                    </button>
                                </div>
                            </form>
                        </section>
                        @else
                        <section class="bg-gray-50 p-6 rounded-lg shadow-md border border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Informasi Status</h3>
                            <p class="text-sm text-gray-600">
                                @if($barang->status == 'sudah diambil')
                                    Barang ini telah dicatat pengambilannya oleh penitip pada tanggal
                                    @if ($barang->transaksiPenitipan && $barang->transaksiPenitipan->tanggal_diambil)
                                        {{ \Carbon\Carbon::parse($barang->transaksiPenitipan->tanggal_diambil)->translatedFormat('d F Y H:i') }}.
                                    @else
                                        N/A.
                                    @endif
                                @elseif($barang->status == 'tersedia')
                                    Barang ini tersedia di gudang.
                                @elseif($barang->status == 'terjual')
                                    Barang ini sudah terjual.
                                @else
                                    Tidak ada aksi pengambilan yang diperlukan untuk status saat ini ({{ Str::title(str_replace('_', ' ', $barang->status)) }}).
                                @endif
                            </p>
                        </section>
                        @endif

                        <section class="bg-gray-50 p-6 rounded-lg shadow-md border border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-700 mb-3">Aksi Lain</h3>
                            <p class="text-sm text-gray-500 mb-3">Saat ini belum ada aksi lain yang tersedia untuk barang ini dari sisi gudang.</p>
                        </section>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection