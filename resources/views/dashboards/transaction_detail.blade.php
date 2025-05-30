<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi - ReUseMart</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Detail Transaksi #{{ $transaksi->id_pembelian }}</h1>
            <a href="{{ route('pembeli.dashboard') }}" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">Kembali ke Dashboard</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Informasi Transaksi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <p><strong>ID Transaksi:</strong> {{ $transaksi->id_pembelian }}</p>
                    <p><strong>Tanggal Pembelian:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal_pembelian)->format('d/m/Y') }}</p>
                    <p><strong>Total:</strong> Rp {{ number_format($transaksi->total, 0, ',', '.') }}</p>
                    <p><strong>Status:</strong> {{ $transaksi->status }}</p>
                </div>
                <div>
                    <p><strong>Metode Pengiriman:</strong> {{ $transaksi->metode_pengiriman ?? 'Tidak ada' }}</p>
                    <p><strong>Bukti Pembayaran:</strong> {{ $transaksi->bukti_pembayaran ?? 'Belum ada' }}</p>
                    @if ($transaksi->tanggal_pengiriman)
                        <p><strong>Tanggal Pengiriman:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal_pengiriman)->format('d/m/Y') }}</p>
                    @else
                        <p><strong>Tanggal Pengiriman:</strong> Belum dikirim</p>
                    @endif
                </div>
            </div>

            <h2 class="text-xl font-semibold mb-4">Detail Barang</h2>
            @if ($transaksi->barangTitipan)
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-4 py-2 text-left">Kode Barang</th>
                                <th class="px-4 py-2 text-left">Nama Barang</th>
                                <th class="px-4 py-2 text-left">Harga</th>
                                <th class="px-4 py-2 text-left">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $transaksi->barangTitipan->kode_barang }}</td>
                                <td class="px-4 py-2">{{ $transaksi->barangTitipan->nama }}</td>
                                <td class="px-4 py-2">Rp {{ number_format($transaksi->barangTitipan->harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">{{ $transaksi->barangTitipan->deskripsi ?? 'Tidak ada deskripsi' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">Tidak ada barang terkait.</p>
            @endif
        </div>
    </div>
</body>
</html>