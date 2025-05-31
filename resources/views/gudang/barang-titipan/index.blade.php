<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang Titipan - Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Daftar Barang Titipan - Pegawai Gudang</h1>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</button>
            </form>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Consigned Items List -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Daftar Barang Titipan</h2>
            @if ($barangTitipan->isEmpty())
                <p class="text-gray-500">Belum ada barang titipan.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($barangTitipan as $barang)
                        <div class="border rounded-lg p-4">
                            <h3 class="text-lg font-medium">{{ $barang->nama }}</h3>
                            <p>Harga: {{ number_format($barang->harga, 0, ',', '.') }}</p>
                            <p>Berat: {{ $barang->berat }} kg</p>
                            <p>Status: {{ $barang->status }}</p>
                            <p>Kondisi: {{ $barang->kondisi }}</p>
                            <p>Kadaluarsa: {{ \Carbon\Carbon::parse($barang->tanggal_kadaluarsa)->format('d-m-Y') }}</p>
                            @if ($barang->perpanjangan)
                                <p>Perpanjangan: Aktif</p>
                            @endif
                            @if ($barang->garansi)
                                <p>Garansi: {{ \Carbon\Carbon::parse($barang->garansi)->format('d-m-Y') }}</p>
                            @endif
                            <!-- Display Photos -->
                            @if (!empty($barang->photos))
                                <div class="flex space-x-2 mt-2">
                                    @foreach (array_slice($barang->photos, 0, 2) as $photo)
                                        <img src="{{ $photo }}" alt="Foto barang" class="w-24 h-24 object-cover rounded">
                                    @endforeach
                                </div>
                            @endif
                            <!-- Action for Recording Pickup -->
                            @if (in_array($barang->status, ['tersedia', 'didonasikan']))
                                <form action="{{ route('gudang.barang-titipan.record-pickup', $barang->kode_barang) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 mt-4">Catat Pengambilan</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>
</html>