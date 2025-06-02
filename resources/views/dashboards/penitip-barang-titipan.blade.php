@extends('layouts.app')

@section('title', 'Manage Barang Titipan')

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
                <h1 class="text-2xl font-semibold text-gray-800">Manage Barang Titipan</h1>
                @if(Auth::guard('penitip')->check())
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Last updated: {{ now()->format('H:i A, d M Y') }}</span>
                    <img src="{{ Auth::guard('penitip')->user()->url_foto ?? 'https://via.placeholder.com/40' }}" alt="User" class="rounded-full w-10 h-10">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Logout</button>
                    </form>
                </div>
                @endif
            </div>

            <div class="bg-white p-4 rounded-lg shadow mb-6">
                {{-- Pastikan $querySearch adalah nama variabel yang dikirim dari controller untuk search query --}}
                <form method="GET" action="{{ route('penitip.barang-titipan.manage') }}" class="flex space-x-4">
                    <input type="text" name="search" placeholder="Search by Name, Price, Weight, Status, or Condition" class="p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-1/2 lg:w-1/3" value="{{ $querySearch ?? '' }}">
                    {{-- CSRF tidak diperlukan untuk GET form --}}
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">Search</button>
                </form>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
                    <p class="font-bold">Success</p>
                    <p>{{ session('success.message') ?? session('success') }}</p>
                </div>
            @endif

            @if (session('error') || $errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') ?? $errors->first() }}</p>
                </div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b">
                                <th class="p-3 text-sm font-medium text-gray-600">Name</th>
                                <th class="p-3 text-sm font-medium text-gray-600">Price</th>
                                <th class="p-3 text-sm font-medium text-gray-600">Weight</th>
                                <th class="p-3 text-sm font-medium text-gray-600">Status</th>
                                <th class="p-3 text-sm font-medium text-gray-600">Condition</th>
                                <th class="p-3 text-sm font-medium text-gray-600">Expiry Date</th>
                                <th class="p-3 text-sm font-medium text-gray-600">Photos</th>
                                <th class="p-3 text-sm font-medium text-gray-600 whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($barangTitipan as $barang)
                                <tr class="border-b hover:bg-gray-50 transition duration-150">
                                    <td class="p-3 text-sm text-gray-700">{{ $barang->nama }}</td>
                                    <td class="p-3 text-sm text-gray-700">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                                    <td class="p-3 text-sm text-gray-700">{{ $barang->berat }} kg</td>
                                    <td class="p-3 text-sm text-gray-700">{{ ($barang->status) }}</td>
                                    <td class="p-3 text-sm text-gray-700">{{ $barang->kondisi }}</td>
                                    <td class="p-3 text-sm text-gray-700">
                                        @if($barang->tanggal_kadaluarsa)
                                            {{ \Carbon\Carbon::parse($barang->tanggal_kadaluarsa)->format('Y-m-d') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="p-3 text-sm text-gray-700">
                                        @if ($barang->fotos && $barang->fotos->count() > 0)
                                            <div class="flex space-x-1">
                                                @foreach ($barang->fotos->take(2) as $foto) {{-- Ambil maksimal 2 foto --}}
                                                    <img src="{{ asset('storage/' . $foto->url_foto) }}" alt="Foto {{ $barang->nama }}" class="w-10 h-10 object-cover rounded">
                                                @endforeach
                                            </div>
                                        @elseif (!empty($barang->photos_from_deskripsi)) {{-- Fallback ke deskripsi JSON jika masih ada --}}
                                            <div class="flex space-x-1">
                                                @foreach (array_slice($barang->photos_from_deskripsi, 0, 2) as $photo_url)
                                                    <img src="{{ $photo_url }}" alt="Photo barang (deskripsi)" class="w-10 h-10 object-cover rounded">
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                                        @php $actionRenderedForPerpanjangan = false; @endphp
                                        {{-- Logika untuk Perpanjangan --}}
                                        @if ($barang->status === 'tersedia')
                                            @if (!$barang->perpanjangan)
                                                <form action="{{ route('penitip.barang-titipan.extend', $barang->kode_barang) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700 transition duration-200 text-xs" onclick="return confirm('Are you sure you want to extend this item?')">Perpanjang</button>
                                                </form>
                                            @else
                                                <button type="button" class="bg-green-600 text-white px-3 py-1 rounded-lg opacity-50 cursor-not-allowed text-xs" disabled>
                                                    Sudah diperpanjang
                                                </button>
                                            @endif
                                            @php $actionRenderedForPerpanjangan = true; @endphp
                                        @endif

                                        {{-- Logika untuk Konfirmasi Pengambilan --}}
                                        @if ($barang->tanggal_kadaluarsa && \Carbon\Carbon::parse($barang->tanggal_kadaluarsa)->isPast())
                                            @if ($barang->status === 'akan diambil')
                                                <button type="button" class="bg-blue-600 text-white px-3 py-1 rounded-lg opacity-50 cursor-not-allowed text-xs @if($actionRenderedForPerpanjangan) ml-1 @endif" disabled>
                                                    Telah Dikonfirmasi
                                                </button>
                                            @elseif (in_array($barang->status, ['tersedia']))
                                                <form action="{{ route('penitip.barang-titipan.confirm-pickup', $barang->kode_barang) }}" method="POST" class="inline @if($actionRenderedForPerpanjangan) ml-1 @endif">
                                                    @csrf
                                                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 transition duration-200 text-xs" onclick="return confirm('Are you sure you want to confirm pickup for this item?')">Konfirmasi Ambil</button>
                                                </form>
                                            @endif
                                             @php $actionRenderedForPerpanjangan = true; @endphp {{-- Set true agar detail selalu ada margin jika ada tombol ini --}}
                                        @endif
                                        
                                        {{-- Tombol Detail --}}
                                        <a href="{{ route('penitip.barang-titipan.show', $barang->kode_barang) }}" 
                                           class="bg-purple-500 text-white px-3 py-1 rounded-lg hover:bg-purple-600 transition duration-200 inline-block text-xs @if($actionRenderedForPerpanjangan) ml-1 @endif">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-3 text-center text-gray-500">No consigned items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $barangTitipan->appends(request()->query())->links() }}
                </div>
            </div>
        </main>
    </div>
@endsection