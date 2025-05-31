@extends('layouts.app')

@section('title', 'Detail Barang Titipan: ' . $barang->nama)

@section('content')
    <div class="flex h-screen bg-gray-100">
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

        <main class="flex-1 p-6 overflow-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Detail Barang: {{ $barang->nama }}</h1>
                <a href="{{ route('penitip.barang-titipan.manage') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition duration-200">
                    <span class="mr-1">&larr;</span> Kembali ke Daftar
                </a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Informasi Barang</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Kode Barang</p>
                            <p class="text-md font-medium text-gray-800">{{ $barang->kode_barang }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Nama Barang</p>
                            <p class="text-md font-medium text-gray-800">{{ $barang->nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Harga</p>
                            <p class="text-md font-medium text-gray-800">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Berat</p>
                            <p class="text-md font-medium text-gray-800">{{ $barang->berat }} kg</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="text-md font-medium text-gray-800">{{ ucfirst($barang->status) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Kondisi</p>
                            <p class="text-md font-medium text-gray-800">{{ $barang->kondisi }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Kategori</p>
                            <p class="text-md font-medium text-gray-800">{{ $barang->subkategori->namaSubKategori ?? '-' }}</p>
                        </div>
                         <div>
                            <p class="text-sm text-gray-500">Penitip</p>
                            <p class="text-md font-medium text-gray-800">{{ $barang->penitip->nama ?? '-' }} ({{$barang->id_penitip}})</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Kadaluarsa</p>
                            <p class="text-md font-medium text-gray-800">{{ $barang->tanggal_kadaluarsa ? \Carbon\Carbon::parse($barang->tanggal_kadaluarsa)->isoFormat('LL') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Garansi</p>
                            <p class="text-md font-medium text-gray-800">{{ $barang->garansi ? \Carbon\Carbon::parse($barang->garansi)->isoFormat('LL') : 'Habis' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Sudah Diperpanjang?</p>
                            <p class="text-md font-medium text-gray-800">{{ $barang->perpanjangan ? 'Ya' : 'Tidak' }}</p>
                        </div>
                         @if($barang->id_pembelian)
                        <div>
                            <p class="text-sm text-gray-500">ID Pembelian</p>
                            <p class="text-md font-medium text-gray-800">{{ $barang->id_pembelian }}</p>
                        </div>
                        @endif
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Deskripsi</p>
                            <div class="mt-1 p-3 border rounded bg-gray-50 text-md text-gray-800 min-h-[60px]">
                                 {!! nl2br(e($barang->deskripsi ?? '-')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Foto Barang</h2>
                    @if($barang->fotos && $barang->fotos->count() > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($barang->fotos as $foto)
                                <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <a href="{{ asset('storage/' . $foto->url_foto) }}" data-fancybox="gallery" data-caption="Foto {{ $barang->nama }} - Urutan {{ $foto->urutan }}">
                                        <img src="{{ asset('storage/' . $foto->url_foto) }}" 
                                             alt="Foto {{ $barang->nama }} - {{ $loop->iteration }}" 
                                             class="w-full h-48 object-cover">
                                    </a>
                                    {{-- Jika Anda ingin menampilkan urutan atau info lain per foto:
                                    <div class="p-2 text-center text-xs text-gray-600">
                                        Urutan: {{ $foto->urutan }}
                                    </div>
                                    --}}
                                </div>
                            @endforeach
                        </div>
                         @if($barang->fotos->count() < 2)
                            <p class="text-sm text-yellow-600 mt-3"><span class="font-bold">Catatan:</span> Barang ini memiliki kurang dari 2 foto.</p>
                        @endif
                    @else
                        <p class="text-gray-500">Tidak ada foto untuk barang ini.</p>
                    @endif
                </div>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
{{-- Jika Anda ingin menggunakan Fancybox untuk galeri foto --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    Fancybox.bind('[data-fancybox="gallery"]', {
      // Fancybox options
    });
  });
</script>
@endpush