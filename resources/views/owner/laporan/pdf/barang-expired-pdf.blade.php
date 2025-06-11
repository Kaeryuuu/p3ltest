<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Barang Expired</title>
    <style>
        body { font-family: sans-serif; margin: 20px; font-size: 11px; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .header h3, .header p { margin: 0; }
        .header { margin-bottom: 1rem; }
        .report-title { margin-bottom: 0.5rem; }
        .info { margin-bottom: 1rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <div class="header text-center">
        <h3 class="font-bold">ReUse Mart</h3>
        <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
    </div>
    <hr style="border-top: 2px solid black; margin-bottom: 1rem;">
    <h4 class="report-title text-center font-bold underline">LAPORAN Barang yang Masa Penitipannya Sudah Habis</h4>
    <div class="info">
        <span>Tanggal cetak : {{ $tanggalCetak }}</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Id Penitip</th>
                <th>Nama Penitip</th>
                <th>Tanggal Masuk</th>
                <th>Tanggal Akhir</th>
                <th>Batas Ambil</th>
            </tr>
        </thead>
        <tbody>
            {{-- DIUBAH: Loop menggunakan $laporanData --}}
            @forelse ($laporanData as $data)
                <tr>
                    <td>{{ $data['barang']->kode_barang }}</td>
                    <td>{{ $data['barang']->nama }}</td>
                    <td>{{ $data['barang']->penitip->id_penitip }}</td>
                    <td>{{ $data['barang']->penitip->nama }}</td>
                    <td>{{ $data['tanggal_masuk']->format('d/m/Y') }}</td>
                    <td>{{ $data['tanggal_akhir']->format('d/m/Y') }}</td>
                    <td>{{ $data['batas_ambil']->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>