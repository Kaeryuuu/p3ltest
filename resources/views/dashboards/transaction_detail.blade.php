<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi - ReUseMart</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Tambahan style jika diperlukan, atau bisa juga langsung di class tailwind */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px; /* pill shape */
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: capitalize;
        }
        .status-paid { background-color: #E6FFFA; color: #2C7A7B; /* Teal-ish green for paid/completed */ }
        .status-pending { background-color: #FFFBEB; color: #B7791F; /* Amber-ish yellow for pending */ }
        .status-shipped { background-color: #EBF4FF; color: #4299E1; /* Blue for shipped */ }
        .status-default { background-color: #F7FAFC; color: #4A5568; /* Light gray for others */ }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-gray-100 min-h-screen font-sans">
    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        
        <header class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-800 mb-4 sm:mb-0">
                    Detail Transaksi
                </h1>
                <a href="{{ route('pembeli.dashboard') }}" class="inline-flex items-center bg-indigo-600 text-white py-2 px-6 rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-150 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
            <p class="text-gray-600 mt-1">Lihat rincian lengkap untuk transaksi <span class="font-semibold text-indigo-600">#{{ $transaksi->id_pembelian }}</span>.</p>
        </header>

        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-xl transition-shadow duration-300 hover:shadow-2xl">
            
            <section class="mb-10">
                <h2 class="text-2xl font-semibold text-gray-700 mb-6 pb-3 border-b border-gray-200">Informasi Transaksi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                    
                    <div class="space-y-3">
                        <p class="flex items-center text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                            <strong class="font-medium text-gray-500 w-40">ID Transaksi:</strong> {{ $transaksi->id_pembelian }}
                        </p>
                        <p class="flex items-center text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <strong class="font-medium text-gray-500 w-40">Tanggal Pembelian:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal_pembelian)->isoFormat('D MMMM YYYY') }}
                        </p>
                         <p class="flex items-center text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            <strong class="font-medium text-gray-500 w-40">Total Pembayaran:</strong> 
                            <span class="font-semibold text-lg text-green-600">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
                        </p>
                        <p class="flex items-center text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <strong class="font-medium text-gray-500 w-40">Status:</strong>
                            <span class="status-badge 
                                @switch(strtolower($transaksi->status))
                                    @case('paid')
                                    @case('completed')
                                    @case('selesai')
                                        status-paid
                                        @break
                                    @case('pending')
                                    @case('menunggu pembayaran')
                                        status-pending
                                        @break
                                    @case('shipped')
                                    @case('dikirim')
                                        status-shipped
                                        @break
                                    @default
                                        status-default
                                @endswitch
                            ">{{ $transaksi->status }}</span>
                        </p>
                    </div>
                    
                    <div class="space-y-3">
                         <p class="flex items-center text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16m-4-1h4m-4-8L8 4m0 0L4 8m4-4v12" /></svg>
                            <strong class="font-medium text-gray-500 w-40">Metode Pengiriman:</strong> {{ $transaksi->metode_pengiriman ?? 'N/A' }}
                        </p>
                        <p class="flex items-center text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            <strong class="font-medium text-gray-500 w-40">Bukti Pembayaran:</strong> 
                            @if($transaksi->bukti_pembayaran)
                                {{-- Jika ini adalah link ke gambar, buat jadi link --}}
                                <a href="{{ asset('storage/bukti_pembayaran/' . $transaksi->bukti_pembayaran) }}" target="_blank" class="text-indigo-600 hover:underline">{{ $transaksi->bukti_pembayaran }}</a>
                            @else
                                Belum ada
                            @endif
                        </p>
                        <p class="flex items-center text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <strong class="font-medium text-gray-500 w-40">Tanggal Pengiriman:</strong> 
                            @if ($transaksi->tanggal_pengiriman)
                                {{ \Carbon\Carbon::parse($transaksi->tanggal_pengiriman)->isoFormat('D MMMM YYYY') }}
                            @else
                                <span class="text-gray-500 italic">Belum dikirim</span>
                            @endif
                        </p>
                         @if ($transaksi->tanggal_pengambilan)
                        <p class="flex items-center text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <strong class="font-medium text-gray-500 w-40">Tanggal Pengambilan:</strong> 
                            {{ \Carbon\Carbon::parse($transaksi->tanggal_pengambilan)->isoFormat('D MMMM YYYY') }}
                        </p>
                        @endif
                    </div>

                </div>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-gray-700 mb-6 pb-3 border-b border-gray-200">Detail Barang</h2>
                @if ($transaksi->barangTitipan)
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Barang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $transaksi->barangTitipan->kode_barang }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $transaksi->barangTitipan->nama }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp {{ number_format($transaksi->barangTitipan->harga, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 break-words min-w-[200px]">{{ $transaksi->barangTitipan->deskripsi ?? 'Tidak ada deskripsi' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-10 bg-gray-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500">Tidak ada detail barang terkait dengan transaksi ini.</p>
                    </div>
                @endif
            </section>
        </div>

        <footer class="text-center mt-12 mb-6">
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} ReUseMart. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>