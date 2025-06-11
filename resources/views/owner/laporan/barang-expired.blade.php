@extends('layouts.app')

@section('title', 'Laporan Barang Expired - ReUseMart')

@section('content')
<main class="flex-1 p-6 bg-gray-100">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Laporan Barang yang Masa Penitipannya Habis</h1>
        <div>
            <a href="{{ route('owner.dashboard') }}" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600">Kembali</a>
            <a href="{{ route('owner.laporan.barang-expired.pdf') }}" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Unduh PDF</a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-lg">
        {{-- Pratinjau Laporan --}}
        <div class="text-center mb-4">
            <h3 class="text-lg font-bold">ReUse Mart</h3>
            <p class="text-sm">Jl. Green Eco Park No. 456 Yogyakarta</p>
        </div>
        <hr class="my-2 border-t-2 border-black">
        <h4 class="text-center font-bold underline mb-2">LAPORAN Barang yang Masa Penitipannya Sudah Habis</h4>
        <div class="text-left text-sm mb-4">
            <span>Tanggal cetak : {{ $tanggalCetak }}</span>
        </div>

        <table class="min-w-full bg-white border border-gray-300 text-sm">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4 border">Kode Produk</th>
                    <th class="py-2 px-4 border">Nama Produk</th>
                    <th class="py-2 px-4 border">Id Penitip</th>
                    <th class="py-2 px-4 border">Nama Penitip</th>
                    <th class="py-2 px-4 border">Tanggal Masuk</th>
                    <th class="py-2 px-4 border">Tanggal Akhir</th>
                    <th class="py-2 px-4 border">Batas Ambil</th>
                </tr>
            </thead>
            <tbody>
                {{-- DIUBAH: Loop menggunakan $laporanData --}}
                @forelse ($laporanData as $data)
                    <tr>
                        <td class="py-2 px-4 border">{{ $data['barang']->kode_barang }}</td>
                        <td class="py-2 px-4 border">{{ $data['barang']->nama }}</td>
                        <td class="py-2 px-4 border">{{ $data['barang']->penitip->id_penitip }}</td>
                        <td class="py-2 px-4 border">{{ $data['barang']->penitip->nama }}</td>
                        <td class="py-2 px-4 border">{{ $data['tanggal_masuk']->format('d/m/Y') }}</td>
                        <td class="py-2 px-4 border">{{ $data['tanggal_akhir']->format('d/m/Y') }}</td>
                        <td class="py-2 px-4 border">{{ $data['batas_ambil']->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Tidak ada barang yang masa penitipannya habis.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection