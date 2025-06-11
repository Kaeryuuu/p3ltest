<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan per Kategori</title>
    <style>
        body { font-family: sans-serif; margin: 20px; font-size: 12px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .header h3, .header p { margin: 0; }
        .header { margin-bottom: 1rem; }
        .report-title { margin-bottom: 0.5rem; }
        .info { display: block; margin-bottom: 1rem; font-size: 11px; }
        .info .date { float: right; }
        .info .year { float: left; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 6px; }
        thead { background-color: #e2e8f0; }
        tfoot { background-color: #e2e8f0; }
    </style>
</head>
<body>
    <div class="header text-center">
        <h3 class="font-bold">ReUse Mart</h3>
        <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
    </div>
    <hr style="border-top: 2px solid black; margin-bottom: 1rem;">
    <h4 class="report-title text-center font-bold underline">LAPORAN PENJUALAN PER KATEGORI BARANG</h4>
    <div class="info">
        <span class="year">Tahun : {{ $tahun }}</span>
        <span class="date">Tanggal cetak : {{ $tanggalCetak }}</span>
    </div>
    <br>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Jumlah item terjual</th>
                <th>Jumlah item gagal terjual</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporanData as $data)
                <tr>
                    <td>{{ $data['kategori'] }}</td>
                    <td class="text-center">{{ $data['terjual'] }}</td>
                    <td class="text-center">{{ $data['gagal_terjual'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot class="font-bold">
            <tr>
                <td class="text-right">Total</td>
                <td class="text-center">{{ $totalTerjual }}</td>
                <td class="text-center">{{ $totalGagalTerjual }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>