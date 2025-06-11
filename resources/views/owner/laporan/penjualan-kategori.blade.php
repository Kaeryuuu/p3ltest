@extends('layouts.app')

@section('title', 'Laporan Penjualan per Kategori - ReUseMart')

@section('content')
<main class="flex-1 p-6 bg-gray-100">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Laporan Penjualan per Kategori</h1>
        <div>
            <a href="{{ route('owner.dashboard') }}" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600">Kembali</a>
            <a href="{{ route('owner.laporan.penjualan-kategori.pdf', ['tahun' => $tahun]) }}" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Unduh PDF</a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-lg">
        {{-- Pratinjau Laporan --}}
        <div class="text-center mb-4">
            <h3 class="text-lg font-bold">ReUse Mart</h3>
            <p class="text-sm">Jl. Green Eco Park No. 456 Yogyakarta</p>
        </div>
        <hr class="my-2 border-t-2 border-black">
        <h4 class="text-center font-bold underline mb-2">LAPORAN PENJUALAN PER KATEGORI BARANG</h4>
        <div class="flex justify-between text-sm mb-4">
            <span>Tahun : {{ $tahun }}</span>
            <span>Tanggal cetak : {{ $tanggalCetak }}</span>
        </div>

        <table class="min-w-full bg-white border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4 border">Kategori</th>
                    <th class="py-2 px-4 border">Jumlah item terjual</th>
                    <th class="py-2 px-4 border">Jumlah item gagal terjual</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporanData as $data)
                    <tr>
                        <td class="py-2 px-4 border">{{ $data['kategori'] }}</td>
                        <td class="py-2 px-4 border text-center">{{ $data['terjual'] }}</td>
                        <td class="py-2 px-4 border text-center">{{ $data['gagal_terjual'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4">Tidak ada data untuk ditampilkan.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-200 font-bold">
                <tr>
                    <td class="py-2 px-4 border text-right">Total</td>
                    <td class="py-2 px-4 border text-center">{{ $totalTerjual }}</td>
                    <td class="py-2 px-4 border text-center">{{ $totalGagalTerjual }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</main>
@endsection