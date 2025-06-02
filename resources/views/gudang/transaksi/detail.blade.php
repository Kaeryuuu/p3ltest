@extends('layouts.app')

@section('title', 'Detail Transaksi')

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
                <a href="{{ route('gudang.transaksi.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-md {{ request()->routeIs('gudang.transaksi.index') || request()->routeIs('gudang.transaksi.detail') ? 'bg-gray-200 text-gray-900 font-semibold' : '' }}">
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
            <div class="mb-8">
                <a href="{{ route('gudang.transaksi.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 hover:underline">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                    Kembali ke Daftar Transaksi
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

            <h1 class="text-3xl font-bold text-gray-800 mb-2">Detail Transaksi</h1>
            <p class="text-gray-600 mb-8">Nomor Transaksi: <span class="font-semibold text-blue-600">#{{ $transaksi->id_pembelian }}</span></p>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-xl">
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4">Informasi Pembelian</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                            <div><strong class="text-gray-600">Pembeli:</strong> <span class="text-gray-800">{{ $transaksi->pembeli->nama ?? 'N/A' }}</span></div>
                            <div><strong class="text-gray-600">Tanggal Pembelian:</strong> <span class="text-gray-800">{{ \Carbon\Carbon::parse($transaksi->tanggal_pembelian)->translatedFormat('d F Y') }}</span></div>
                            <div><strong class="text-gray-600">Total Harga Barang:</strong> <span class="text-gray-800">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span></div>
                            <div><strong class="text-gray-600">Ongkir:</strong> <span class="text-gray-800">Rp {{ number_format($transaksi->ongkir, 0, ',', '.') }}</span></div>
                            <div><strong class="text-gray-600">Poin Digunakan:</strong> <span class="text-gray-800">{{ $transaksi->poin_diskon ?? 0 }}</span></div>
                            <div><strong class="text-gray-600">Total Akhir Dibayar:</strong> <span class="text-gray-800 font-bold text-blue-700">Rp {{ number_format($transaksi->total_akhir, 0, ',', '.') }}</span></div>
                            <div><strong class="text-gray-600">Poin Diperoleh:</strong> <span class="text-gray-800">{{ $transaksi->poin_diperoleh ?? 0 }}</span></div>
                            <div><strong class="text-gray-600">Tanggal Pembayaran:</strong> <span class="text-gray-800">{{ $transaksi->tanggal_pembayaran ? \Carbon\Carbon::parse($transaksi->tanggal_pembayaran)->translatedFormat('d F Y, H:i') : 'Belum Dibayar' }}</span></div>
                        </div>
                    </div>

                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4">Detail Barang Dalam Transaksi Ini</h2>
                        @if ($transaksi->barangTitipan->isNotEmpty())
                            <div class="space-y-6">
                                @foreach ($transaksi->barangTitipan as $index => $barang)
                                    <div class="p-4 border rounded-md @if(!$loop->last) border-b-2 pb-4 mb-4 @endif">
                                        <h3 class="text-md font-semibold text-blue-700 mb-3">Barang #{{ $index + 1 }}: {{ $barang->nama }} ({{$barang->kode_barang}})</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-sm mb-3">
                                            <div><strong class="text-gray-600">Harga Satuan:</strong> <span class="text-gray-800">Rp {{ number_format($barang->harga, 0, ',', '.') }}</span></div>
                                            <div><strong class="text-gray-600">Berat:</strong> <span class="text-gray-800">{{ $barang->berat }} kg</span></div>
                                            <div><strong class="text-gray-600">Kondisi:</strong> <span class="text-gray-800">{{ Str::title($barang->kondisi) }}</span></div>
                                            <div><strong class="text-gray-600">Kadaluarsa:</strong> <span class="text-gray-800">{{ $barang->tanggal_kadaluarsa ? \Carbon\Carbon::parse($barang->tanggal_kadaluarsa)->translatedFormat('d F Y') : 'Tidak Ada' }}</span></div>
                                            <div><strong class="text-gray-600">Penitip:</strong> <span class="text-gray-800">{{ $barang->penitip->nama ?? 'N/A' }}</span></div>
                                        </div>

                                        @if ($barang->komisi)
                                            <div class="mt-3 pt-3 border-t border-dashed">
                                                <h4 class="text-sm font-semibold text-gray-700 mb-1">Detail Komisi untuk Barang Ini:</h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-1 text-xs">
                                                    <div><strong class="text-gray-500">Komisi Mart:</strong> <span class="text-gray-700">Rp {{ number_format($barang->komisi->komisi_mart, 0, ',', '.') }}</span></div>
                                                    <div><strong class="text-gray-500">Komisi Penitip:</strong> <span class="text-gray-700">Rp {{ number_format($barang->komisi->komisi_penitip, 0, ',', '.') }}</span></div>
                                                    @if ($barang->komisi->komisi_hunter > 0)
                                                        <div><strong class="text-gray-500">Komisi Hunter:</strong> <span class="text-gray-700">Rp {{ number_format($barang->komisi->komisi_hunter, 0, ',', '.') }}</span></div>
                                                    @endif
                                                    @if ($barang->komisi->bonus > 0)
                                                        <div><strong class="text-gray-500">Bonus Penitip:</strong> <span class="text-gray-700">Rp {{ number_format($barang->komisi->bonus, 0, ',', '.') }}</span></div>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($transaksi->tanggal_pembayaran)
                                             <div class="mt-3 pt-3 border-t border-dashed">
                                                <p class="text-xs text-yellow-600 italic">Komisi untuk barang ini belum diproses.</p>
                                             </div>
                                        @endif

                                        @if ($barang->fotos->isNotEmpty())
                                            <div class="mt-4">
                                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Foto Barang Ini:</h4>
                                                <div class="flex space-x-2 overflow-x-auto">
                                                    @foreach ($barang->fotos as $foto)
                                                        <a href="{{ Storage::url($foto->url_foto) }}" data-fancybox="gallery-{{$barang->kode_barang}}" data-caption="Foto {{ $barang->nama }}">
                                                            <img src="{{ Storage::url($foto->url_foto) }}" alt="Foto Barang {{ $barang->nama }}" class="w-24 h-24 object-cover rounded-md border border-gray-300 shadow-sm">
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 italic">Tidak ada barang terkait transaksi ini.</p>
                        @endif
                    </div>

                    <div>
                        <h2 class="text-xl font-semibold text-gray-700 mb-4">Status & Pengiriman</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                            <div>
                                <strong class="text-gray-600">Status Transaksi:</strong>
                                <span class="ml-2 px-3 py-1 text-xs font-semibold rounded-full
                                    @if($transaksi->status == 'sedang dikemas') bg-yellow-100 text-yellow-800
                                    @elseif($transaksi->status == 'siap diambil') bg-blue-100 text-blue-800
                                    @elseif($transaksi->status == 'sedang dikirim') bg-indigo-100 text-indigo-800
                                    @elseif($transaksi->status == 'sudah diambil') bg-purple-100 text-purple-800
                                    @elseif($transaksi->status == 'akan diambil') bg-cyan-100 text-cyan-800
                                    @elseif($transaksi->status == 'selesai') bg-green-100 text-green-800
                                    @elseif($transaksi->status == 'dibatalkan') bg-red-100 text-red-800
                                    @elseif($transaksi->status == 'hangus') bg-pink-100 text-pink-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ Str::title(str_replace('_', ' ', $transaksi->status)) }}
                                </span>
                            </div>
                            <div><strong class="text-gray-600">Metode Pengiriman:</strong> <span class="text-gray-800">{{ $transaksi->metode_pengiriman ? Str::title($transaksi->metode_pengiriman) : 'N/A' }}</span></div>
                            @if ($transaksi->metode_pengiriman == 'dikirim')
                                @if ($transaksi->tanggal_pengiriman)
                                    <div><strong class="text-gray-600">Tanggal Pengiriman:</strong> <span class="text-gray-800">{{ \Carbon\Carbon::parse($transaksi->tanggal_pengiriman)->translatedFormat('d F Y') }}</span></div>
                                @endif
                                @if ($transaksi->kurir)
                                    <div><strong class="text-gray-600">Kurir:</strong> <span class="text-gray-800">{{ $transaksi->kurir->nama }}</span></div>
                                @endif
                                <div><strong class="text-gray-600">Alamat Pengiriman:</strong> <span class="text-gray-800">{{ $transaksi->alamat ?: 'Tidak Ada' }}</span></div>
                            @endif
                            @if ($transaksi->metode_pengiriman == 'pickup')
                                @if ($transaksi->tanggal_pengambilan)
                                    <div><strong class="text-gray-600">
                                        @if ($transaksi->status == 'selesai' || $transaksi->status == 'sudah diambil')
                                            Tanggal Pengambilan:
                                        @else
                                            Dijadwalkan Diambil:
                                        @endif
                                    </strong> <span class="text-gray-800">{{ \Carbon\Carbon::parse($transaksi->tanggal_pengambilan)->translatedFormat($transaksi->status == 'selesai' || $transaksi->status == 'sudah diambil' ? 'd F Y, H:i' : 'd F Y') }}</span></div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6">
                    @if ($transaksi->status == 'sedang dikemas' && $transaksi->metode_pengiriman == 'dikirim')
                        <div class="bg-white p-6 rounded-xl shadow-xl">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Jadwalkan Pengiriman</h3>
                            <form method="POST" action="{{ route('gudang.transaksi.schedule-delivery', $transaksi->id_pembelian) }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="tanggal_pengiriman" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengiriman</label>
                                    <input type="date" name="tanggal_pengiriman" id="tanggal_pengiriman" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required min="{{ now('Asia/Jakarta')->toDateString() }}">
                                    @error('tanggal_pengiriman')
                                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="id_kurir" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kurir</label>
                                    <select name="id_kurir" id="id_kurir" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                        <option value="">Pilih Kurir...</option>
                                        {{-- Assuming you pass $kurirs from controller or query here --}}
                                        @foreach (\App\Models\Pegawai::where('id_jabatan', 5)->where('status', 'Active')->get() as $kurir_option)
                                            <option value="{{ $kurir_option->id_pegawai }}">{{ $kurir_option->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_kurir')
                                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="w-full flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Jadwalkan Pengiriman
                                </button>
                            </form>
                        </div>
                    @endif

                    @if ($transaksi->status == 'siap diambil' && $transaksi->metode_pengiriman == 'pickup')
                        <div class="bg-white p-6 rounded-xl shadow-xl">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Jadwalkan Pengambilan oleh Pembeli</h3>
                            <form method="POST" action="{{ route('gudang.transaksi.schedule-pickup', $transaksi->id_pembelian) }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="tanggal_pengambilan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengambilan (Perkiraan)</label>
                                    <input type="date" name="tanggal_pengambilan" id="tanggal_pengambilan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required min="{{ now('Asia/Jakarta')->toDateString() }}">
                                    @error('tanggal_pengambilan')
                                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="w-full flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Jadwalkan Pengambilan
                                </button>
                            </form>
                        </div>
                    @endif

                    @if ($transaksi->status == 'akan diambil' && $transaksi->metode_pengiriman == 'pickup')
                        <div class="bg-white p-6 rounded-xl shadow-xl">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Konfirmasi Pengambilan Oleh Pembeli</h3>
                            <form method="POST" action="{{ route('gudang.transaksi.confirm-pickup', $transaksi->id_pembelian) }}" class="mt-4">
                                @csrf
                                <p class="text-sm text-gray-600 mb-4">Pastikan barang sudah diterima oleh pembeli sebelum melakukan konfirmasi.</p>
                                <button type="submit" class="w-full flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Konfirmasi Barang Telah Diambil
                                </button>
                            </form>
                        </div>
                    @endif
                    
                    @if ($transaksi->status == 'transaksi selesai')
                        <div class="bg-white p-6 rounded-xl shadow-xl text-center">
                            <svg class="mx-auto h-12 w-12 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-700 mt-3">Transaksi Selesai</h3>
                            <p class="text-sm text-gray-600 mt-1">Tidak ada aksi lebih lanjut yang diperlukan untuk transaksi ini dari sisi gudang.</p>
                        </div>
                    @endif

                    {{-- Allow printing invoice for most relevant statuses --}}
                    @if (in_array($transaksi->status, ['sedang dikemas', 'siap diambil', 'akan diambil', 'sedang dikirim', 'sudah diambil', 'transaksi selesai', 'hangus']))
                        <div class="bg-white p-6 rounded-xl shadow-xl text-center">
                            <a href="{{ route('gudang.transaksi.print-invoice', $transaksi->id_pembelian) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Cetak Nota
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
    {{-- Include Fancybox JS and CSS if not globally included --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" /> --}}
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script> --}}
@endsection