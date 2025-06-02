<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Penjualan (Pickup) #{{ $transaksi->no_nota_pembelian ?? $transaksi->id_pembelian }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            margin: 15px;
            color: #333;
        }
        .container {
            width: 100%;
        }
        h1 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 5px;
            color: #007bff;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .header p {
            margin: 2px 0;
            font-size: 10px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 8px;
            color: #555;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 3px;
        }
        table.info-table {
            width: 100%;
            margin-bottom: 10px;
        }
        table.info-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        table.info-table .label {
            font-weight: bold;
            width: 120px;
        }

        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        table.items-table th, table.items-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        table.items-table th {
            background-color: #f8f8f8;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-section {
            margin-top: 15px;
            float: right;
            width: 50%;
        }
        .totals-section table {
            width: 100%;
        }
        .totals-section .value {
            font-weight: bold;
            text-align: right;
        }
        .totals-section .grand-total .label, .totals-section .grand-total .value {
            font-size: 14px;
            padding-top: 8px;
            border-top: 2px solid #333;
        }
        .signature-section {
            margin-top: 40px;
            clear: both;
        }
        .signature-section table {
            width: 100%;
        }
        .signature-section td {
            width: 50%; /* Adjusted for two signatures */
            text-align: center;
            padding-top: 40px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>NOTA PENJUALAN</h1>
            <p><strong>REUSE MART</strong></p>
            <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
            <p>(Diambil oleh Pembeli)</p>
        </div>

        <table class="info-table section">
            <tr>
                <td class="label">No Nota:</td>
                <td>{{ $transaksi->no_nota_pembelian ?? $transaksi->id_pembelian }}</td>
                <td class="label">Tanggal Pesan:</td>
                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_pembelian)->format('d M Y, H:i') }}</td>
            </tr>
            <tr>
                <td class="label">Lunas Pada:</td>
                <td>{{ $transaksi->tanggal_pembayaran ? \Carbon\Carbon::parse($transaksi->tanggal_pembayaran)->format('d M Y, H:i') : '-' }}</td>
                <td class="label">Tanggal Ambil:</td>
                <td>{{ $transaksi->tanggal_pengambilan ? \Carbon\Carbon::parse($transaksi->tanggal_pengambilan)->format('d M Y, H:i') : '-' }}</td>
            </tr>
        </table>

        <div class="section">
            <div class="section-title">Informasi Pembeli</div>
             <table class="info-table">
                <tr>
                    <td class="label">Pembeli:</td>
                    <td>{{ $transaksi->pembeli->nama ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Metode:</td>
                    <td>Diambil Sendiri</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Detail Barang</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:5%;">No.</th>
                        <th>Nama Barang</th>
                        <th class="text-center" style="width:10%;">Qty</th>
                        <th class="text-right" style="width:25%;">Harga Satuan</th>
                        <th class="text-right" style="width:25%;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @if($transaksi->barangTitipan->isNotEmpty())
                        @foreach($transaksi->barangTitipan as $index => $barang)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $barang->nama }} <small>({{ $barang->kode_barang }})</small></td>
                            <td class="text-center">1</td>
                            <td class="text-right">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center"><em>Tidak ada barang dalam transaksi ini.</em></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="totals-section clearfix">
            <table>
                <tr>
                    <td class="label">Subtotal Barang:</td>
                    <td class="value">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                </tr>
                {{-- Ongkir biasanya 0 untuk pickup, tapi tampilkan jika ada --}}
                @if(isset($transaksi->ongkir) && $transaksi->ongkir > 0)
                <tr>
                    <td class="label">Ongkos Kirim:</td>
                    <td class="value">Rp {{ number_format($transaksi->ongkir, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if(isset($transaksi->poin_diskon) && $transaksi->poin_diskon > 0)
                <tr>
                    <td class="label">Potongan Poin ({{ $transaksi->poin_diskon }} poin):</td>
                    <td class="value">- Rp {{ number_format($transaksi->poin_diskon * 100, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="grand-total">
                    <td class="label">TOTAL BAYAR:</td>
                    <td class="value">Rp {{ number_format($transaksi->total_akhir, 0, ',', '.') }}</td>
                </tr>
                 <tr>
                    <td class="label" style="font-size:10px;">Poin Diperoleh:</td>
                    <td class="value" style="font-size:10px;">{{ $transaksi->poin_diperoleh ?? 0 }} Poin</td>
                </tr>
                @if($transaksi->pembeli)
                <tr>
                    <td class="label" style="font-size:10px;">Total Poin Customer:</td>
                    <td class="value" style="font-size:10px;">{{ $transaksi->pembeli->poin_loyalitas ?? 0 }} Poin</td>
                </tr>
                @endif
            </table>
        </div>
        <div class="clearfix"></div>

        <div class="signature-section">
            <table>
                <tr>
                    <td>Diserahkan Oleh,<br>(QC ReUseMart)</td>
                    <td>Diterima Oleh,<br>(Pembeli)</td>
                </tr>
                <tr>
                    <td><br><br><br>({{ Auth::guard('pegawai')->user()->nama ?? 'Staff Gudang' }})</td>
                    <td><br><br><br>({{ $transaksi->pembeli->nama ?? '_________________' }})</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Terima kasih atas kepercayaan Anda! Simpan nota ini sebagai bukti pembelian.</p>
            <p>Dicetak pada: {{ now()->format('d M Y, H:i') }}</p>
        </div>
    </div>
</body>
</html>