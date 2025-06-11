<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\BarangTitipan;
use App\Models\TransaksiPenitipan;
use App\Models\TransaksiPembelian;
use App\Models\Komisi;
use App\Models\Penitip;
use App\Models\Pembeli;
use App\Models\KategoriBarang; // <-- DITAMBAHKAN: Diperlukan untuk laporan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PegawaiController extends Controller
{
    public function __construct()
    {
        // Middleware untuk Pegawai Gudang (id_jabatan 4)
        $this->middleware(function ($request, $next) {
            if (!Auth::guard('pegawai')->check() || !Auth::guard('pegawai')->user()->jabatan) {
                abort(403, 'Akses ditolak. Informasi pegawai tidak lengkap.');
            }
            if (Auth::guard('pegawai')->user()->jabatan->id_jabatan !== 4) {
                abort(403, 'Akses ditolak. Hanya Pegawai Gudang yang dapat mengakses halaman ini.');
            }
            return $next($request);
        })->only([
            'barangTitipanIndex',
            'showBarangTitipanDetail',
            'recordPickup',
            'transaksiIndex',
            'transaksiDetail',
            'scheduleDelivery',
            'confirmPickup',
            'schedulePickup',
        ]);

        // --- DITAMBAHKAN: Middleware untuk Owner (asumsi id_jabatan 1) ---
        $this->middleware(function ($request, $next) {
            if (!Auth::guard('pegawai')->check() || !Auth::guard('pegawai')->user()->jabatan) {
                abort(403, 'Akses ditolak. Informasi pegawai tidak lengkap.');
            }
            // Asumsi ID Jabatan untuk Owner adalah 1
            if (Auth::guard('pegawai')->user()->jabatan->id_jabatan !== 1) {
                abort(403, 'Akses ditolak. Hanya Owner yang dapat mengakses halaman ini.');
            }
            return $next($request);
        })->only([
            'laporanPenjualanKategori',
            'downloadPenjualanKategoriPDF',
            'laporanBarangExpired',
            'downloadBarangExpiredPDF'
        ]);
    }

    public function index()
    {
        $pegawai = Pegawai::all();
        return response()->json($pegawai);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pegawai' => 'required|string|max:11|unique:pegawai,id_pegawai',
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:pegawai,email',
            'password' => 'required|string|max:255',
            'status' => 'nullable|string|max:20',
        ]);
        $pegawai = Pegawai::create($validated);
        return response()->json($pegawai, 201);
    }

    public function show($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return response()->json($pegawai);
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $validated = $request->validate([
            'id_jabatan' => 'sometimes|required|exists:jabatan,id_jabatan',
            'nama' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|max:100|unique:pegawai,email,' . $id . ',id_pegawai',
            'password' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|nullable|string|max:20',
        ]);
        $pegawai->update($validated);
        return response()->json($pegawai);
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();
        return response()->json(null, 204);
    }

    public function barangTitipanIndex()
    {
        $barangTitipan = BarangTitipan::whereIn('status', ['tersedia', 'didonasikan', 'akan diambil', 'diambil kembali', 'terjual', 'hangus'])
            ->with(['transaksiPenitipan', 'fotos'])
            ->orderBy('kode_barang', 'desc')
            ->get();

        return view('gudang.barang-titipan.index', compact('barangTitipan'));
    }

    public function showBarangTitipanDetail($kode_barang)
    {
        $barang = BarangTitipan::with(['fotos', 'penitip', 'transaksiPenitipan', 'subkategori', 'kategori'])
                            ->where('kode_barang', $kode_barang)
                            ->firstOrFail();
        return view('gudang.barang-titipan.detail', compact('barang'));
    }

    public function recordPickup(Request $request, $kode_barang)
    {
        $barang = BarangTitipan::where('kode_barang', $kode_barang)
            ->where('status', 'akan diambil')
            ->firstOrFail();
        
        $transaksiDataPenitipan = $barang->transaksiPenitipan;
        
        if (!$transaksiDataPenitipan) {
            return redirect()->route('gudang.barang-titipan.detail', $barang->kode_barang)
                             ->with('error', 'Data transaksi penitipan yang relevan tidak ditemukan untuk barang "' . $barang->nama . '".');
        }
            
        $transaksiDataPenitipan->tanggal_diambil = now();
        $transaksiDataPenitipan->id_pegawai = Auth::guard('pegawai')->user()->id_pegawai;
        $transaksiDataPenitipan->save();
        
        $barang->status = 'diambil kembali';
        $barang->save();

        return redirect()->route('gudang.barang-titipan.detail', $barang->kode_barang)
                         ->with('success', 'Pengambilan barang "' . $barang->nama . '" berhasil dicatat.');
    }

    public function transaksiIndex()
    {
        $transaksi = TransaksiPembelian::whereIn('metode_pengiriman', ['pickup', 'dikirim'])
            ->with(['barangTitipan.fotos', 'pembeli'])
            ->orderBy('id_pembelian', 'asc')
            ->get();
        return view('gudang.transaksi.index', compact('transaksi'));
    }

    public function transaksiDetail($id_pembelian)
    {
        $transaksi = TransaksiPembelian::with([
            'barangTitipan.fotos',
            'barangTitipan.komisi',
            'barangTitipan.penitip',
            'barangTitipan.transaksiPenitipan.pegawai',
            'pembeli',
            'kurir'
        ])->findOrFail($id_pembelian);

        if ($transaksi->tanggal_pembayaran && $transaksi->barangTitipan->isNotEmpty()) {
            $itemsToProcessCommissionFor = $transaksi->barangTitipan->filter(function ($barang) {
                return !$barang->komisi;
            });

            if ($itemsToProcessCommissionFor->isNotEmpty()) {
                Log::info("[PegawaiController][transaksiDetail] Transaksi ID {$id_pembelian}: Pembayaran ada. Ditemukan {$itemsToProcessCommissionFor->count()} item(s) yang komisinya belum diproses. Memulai proses.");
                DB::beginTransaction();
                try {
                    foreach ($itemsToProcessCommissionFor as $barang) {
                        Log::info("[PegawaiController][transaksiDetail] Memproses komisi untuk barang {$barang->kode_barang} dalam Transaksi ID {$id_pembelian}.");

                        $penitip = $barang->penitip;
                        $transaksiOriginalPenitipan = $barang->transaksiPenitipan;
                        $pegawaiHunter = null;
                        $id_hunter = null;

                        if ($transaksiOriginalPenitipan && $transaksiOriginalPenitipan->pegawai) {
                            $pegawaiHunter = $transaksiOriginalPenitipan->pegawai;
                        }

                        if (!$penitip) {
                            Log::error("Penitip tidak ditemukan untuk Barang {$barang->kode_barang} dalam Transaksi ID {$id_pembelian}. Komisi untuk item ini dilewati.");
                            throw new \Exception("Penitip tidak ditemukan untuk Barang {$barang->kode_barang}");
                        }
                        
                        $hargaJual = $barang->harga;
                        $komisi_hunter = 0;
                        $komisi_mart_final = 0;
                        $bonus_penitip_cepat = 0;

                        $persentaseKomisiMart = $barang->perpanjangan ? 0.30 : 0.20;
                        if ($pegawaiHunter && $pegawaiHunter->id_jabatan == 6) {
                            $komisi_hunter = $hargaJual * 0.05;
                            $persentaseKomisiMart = $barang->perpanjangan ? 0.25 : 0.15; 
                            $id_hunter = $pegawaiHunter->id_pegawai;
                        }
                        $komisi_mart_final = $hargaJual * $persentaseKomisiMart;

                        if ($barang->terjual_cepat == 1) {
                            $bonus_penitip_cepat = $komisi_mart_final * 0.10;
                            
                            Log::info("[PegawaiController][transaksiDetail] Bonus penitip cepat diterapkan untuk barang {$barang->kode_barang}.");
                        }

                        $penghasilan_kotor_penitip = $hargaJual - $komisi_mart_final - $komisi_hunter;
                        $komisi_mart_final -= $bonus_penitip_cepat;
                        $komisi_penitip_final = $penghasilan_kotor_penitip + $bonus_penitip_cepat;

                        $penitip->saldo = ($penitip->saldo ?? 0) + round($komisi_penitip_final, 2);
                        $ratingLama = $penitip->rating ?? 0; 
                        $jumlahJualLama = $penitip->jumlah_jual ?? 0; 
                        $ratingUntukPenjualanIni = 4; 
                        $totalRatingPoinLama = $ratingLama * $jumlahJualLama;
                        $jumlahJualBaru = $jumlahJualLama + 1;
                        $penitip->jumlah_jual = $jumlahJualBaru;
                        $totalRatingPoinBaru = $totalRatingPoinLama + $ratingUntukPenjualanIni;
                        if ($jumlahJualBaru > 0) {
                            $penitip->rating = round($totalRatingPoinBaru / $jumlahJualBaru, 2); 
                        } else {
                            $penitip->rating = 0; 
                        }
                        $penitip->save();
                        Log::info("[PegawaiController][transaksiDetail] Penitip ID {$penitip->id_penitip} (barang {$barang->kode_barang}) saldo/rating diperbarui.");

                        Komisi::create([
                            'kode_barang' => $barang->kode_barang,
                            'id_pegawai' => $id_hunter,
                            'id_penitip' => $penitip->id_penitip,
                            'komisi_hunter' => round($komisi_hunter, 2),
                            'komisi_mart' => round($komisi_mart_final, 2),
                            'komisi_penitip' => round($komisi_penitip_final, 2),
                            'bonus' => round($bonus_penitip_cepat, 2),
                        ]);
                        Log::info("[PegawaiController][transaksiDetail] Komisi untuk barang {$barang->kode_barang} dalam Transaksi ID {$id_pembelian} berhasil dibuat.");
                        $barang->load('komisi');
                    }

                    $pembeli = $transaksi->pembeli;
                    if ($pembeli) {
                        $totalAkhir = $transaksi->total_akhir;
                        $poinDasar = floor($totalAkhir / 10000);
                        $bonusPoinTambahan = ($totalAkhir > 500000) ? floor($poinDasar * 0.20) : 0;
                        $totalPoinDiperoleh = $poinDasar + $bonusPoinTambahan;
                        $pembeli->poin_loyalitas = ($pembeli->poin_loyalitas ?? 0) + $totalPoinDiperoleh;
                        $pembeli->save();
                        Log::info("[PegawaiController][transaksiDetail] Pembeli ID {$pembeli->id_pembeli} poin_loyalitas updated to {$pembeli->poin_loyalitas} untuk Transaksi ID {$id_pembelian}");
                    } elseif (!$pembeli && $transaksi->id_pembeli) {
                        Log::warning("[PegawaiController][transaksiDetail] Pembeli tidak ditemukan untuk Transaksi ID {$transaksi->id_pembelian}, poin tidak akan diproses.");
                    }

                    DB::commit();
                    Log::info("[PegawaiController][transaksiDetail] Transaksi ID {$id_pembelian}: Kalkulasi komisi untuk item yang membutuhkan selesai dan di-commit.");
                    $transaksi->load('barangTitipan.komisi');

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("[PegawaiController][transaksiDetail] Gagal memproses komisi saat akses detail transaksi ID {$id_pembelian}: " . $e->getMessage(), [
                        'trace' => substr($e->getTraceAsString(), 0, 1000)
                    ]);
                }
            } elseif ($transaksi->tanggal_pembayaran && $transaksi->barangTitipan->isEmpty()) {
                Log::info("[PegawaiController][transaksiDetail] Transaksi ID {$id_pembelian}: Pembayaran ada, namun semua item sudah memiliki komisi. Skip proses komisi.");
            } else {
                if (!$transaksi->tanggal_pembayaran) {
                    Log::info("[PegawaiController][transaksiDetail] Transaksi ID {$id_pembelian}: Pembayaran belum ada, skip proses komisi.");
                } elseif ($transaksi->barangTitipan->isEmpty()) {
                    Log::warning("[PegawaiController][transaksiDetail] Transaksi ID {$id_pembelian}: Tidak ada BarangTitipan terkait, skip proses komisi.");
                }
            }
        }

        return view('gudang.transaksi.detail', compact('transaksi'));
    }

    public function scheduleDelivery(Request $request, $id_pembelian)
    {
        $transaksi = TransaksiPembelian::findOrFail($id_pembelian);
        if ($transaksi->status !== 'sedang dikemas' || $transaksi->metode_pengiriman !== 'dikirim') {
            return redirect()->route('gudang.transaksi.detail', $id_pembelian)->with('error', 'Pengiriman tidak dapat dijadwalkan untuk transaksi ini.');
        }
        $validated = $request->validate([
            'tanggal_pengiriman' => [
                'required', 'date', 'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    $date = Carbon::parse($value, 'Asia/Jakarta')->startOfDay();
                    $today = Carbon::now('Asia/Jakarta')->startOfDay();
                    if ($date->isSameDay($today) && Carbon::now('Asia/Jakarta')->hour >= 16) {
                        $fail('Pengiriman setelah jam 4 sore tidak dapat dijadwalkan untuk hari ini. Silakan pilih tanggal lain.');
                    }
                },
            ],
            'id_kurir' => 'required|exists:pegawai,id_pegawai',
        ]);
        $transaksi->tanggal_pengiriman = $validated['tanggal_pengiriman'];
        $transaksi->id_kurir = $validated['id_kurir'];
        $transaksi->status = 'sedang dikirim';
        $transaksi->save();
        return redirect()->route('gudang.transaksi.detail', $id_pembelian)->with('success', 'Pengiriman berhasil dijadwalkan.');
    }

    public function confirmPickup(Request $request, $id_pembelian)
    {
        $transaksi = TransaksiPembelian::findOrFail($id_pembelian);
        if ($transaksi->status !== 'akan diambil' || $transaksi->metode_pengiriman !== 'pickup') { 
             return redirect()->route('gudang.transaksi.detail', $id_pembelian)->with('error', 'Konfirmasi pengambilan tidak dapat dilakukan untuk transaksi ini.');
        }
        $transaksi->status = 'selesai'; 
        $transaksi->tanggal_pengambilan = now(); 
        $transaksi->save();

        if ($transaksi->barangTitipan->isNotEmpty()) {
            foreach($transaksi->barangTitipan as $barang) {
                $barang->status = 'terjual'; 
                $barang->save();
            }
        }
        return redirect()->route('gudang.transaksi.detail', $id_pembelian)->with('success', 'Pengambilan barang oleh pembeli berhasil dikonfirmasi. Transaksi selesai.'); 
    }

    public function schedulePickup(Request $request, $id_pembelian)
    {
        $transaksi = TransaksiPembelian::findOrFail($id_pembelian);
        if ($transaksi->status !== 'siap diambil' || $transaksi->metode_pengiriman !== 'pickup') { 
            return redirect()->route('gudang.transaksi.detail', $id_pembelian)->with('error', 'Penjadwalan pengambilan tidak dapat dilakukan untuk transaksi ini.');
        }
        $validated = $request->validate([
            'tanggal_pengambilan' => 'required|date|after_or_equal:today', 
        ]);
        $transaksi->tanggal_pengambilan = $validated['tanggal_pengambilan']; 
        $transaksi->status = 'akan diambil'; 
        $transaksi->save();
        return redirect()->route('gudang.transaksi.detail', $id_pembelian)->with('success', 'Pengambilan oleh pembeli berhasil dijadwalkan.'); 
    }

    public function generateInvoicePDF($id_pembelian)
    {
        $transaksi = TransaksiPembelian::with([
            'pembeli',
            'kurir',
            'barangTitipan.penitip',
            'barangTitipan.fotos'
        ])->findOrFail($id_pembelian);

        $viewName = $transaksi->metode_pengiriman === 'dikirim' ? 'invoice-kurir' : 'invoice-pickup';

        $data = [
            'transaksi' => $transaksi,
            'nama_toko' => 'ReUseMart',
            'alamat_toko' => 'Jl. ReUse No. 1, Yogyakarta',
            'telepon_toko' => '0812-3456-7890',
        ];

        $pdf = Pdf::loadView($viewName, $data);
        $fileName = "nota-penjualan-{$transaksi->id_pembelian}-{$transaksi->no_nota_pembelian}.pdf";
        return $pdf->download($fileName);
    }
    
    // ==========================================================================================
    // == METHOD BARU UNTUK LAPORAN OWNER
    // ==========================================================================================

    /**
     * Menampilkan halaman Laporan Penjualan per Kategori untuk Owner.
     */
    public function laporanPenjualanKategori(Request $request)
    {
        $tahun = $request->input('tahun', Carbon::now()->year);

        // Ambil semua kategori dengan relasi yang dibutuhkan
        // Kita tidak memfilter tahun di sini, akan dilakukan di PHP
        $kategoriData = \App\Models\Kategoribarang::with([
            'barangTitipan.transaksiPenitipan', // Untuk status 'hangus' dan 'diambil kembali'
            'barangTitipan.transaksiPembelian'  // Untuk status 'terjual'
        ])->get();

        $laporanData = [];
        $totalTerjual = 0;
        $totalGagalTerjual = 0;

        foreach ($kategoriData as $kategori) {
            $terjualCount = 0;
            $gagalTerjualCount = 0;

            foreach ($kategori->barangTitipan as $barang) {
                $cocok = false;
                if ($barang->status == 'terjual' && $barang->transaksiPembelian) {
                    // Cek tahun dari tanggal pembayaran di transaksi pembelian
                    if (Carbon::parse($barang->transaksiPembelian->tanggal_pembayaran)->year == $tahun) {
                        $terjualCount++;
                        $cocok = true;
                    }
                } elseif ($barang->status == 'diambil kembali' && $barang->transaksiPenitipan) {
                    if (Carbon::parse($barang->transaksiPenitipan->tanggal_diambil)->year == $tahun) {
                        $gagalTerjualCount++;
                        $cocok = true;
                    }
                } elseif ($barang->status == 'didonasikan' && $barang->transaksiPenitipan) {
                    if (Carbon::parse($barang->transaksiPenitipan->tanggal_akhir)->year == $tahun) {
                        $terjualCount++;
                        $cocok = true;
                    }
                }
                elseif ($barang->status == 'disumbangkan' && $barang->transaksiPenitipan) {
                    if (Carbon::parse($barang->transaksiPenitipan->tanggal_akhir)->year == $tahun) {
                        $terjualCount++;
                        $cocok = true;
                    }
                }
                elseif ($barang->status == 'tersedia' && $barang->transaksiPenitipan) {
                    if (Carbon::parse($barang->transaksiPenitipan->tanggal_akhir)->year == $tahun) {
                        $gagalTerjualCount++;
                        $cocok = true;
                    }
                }
            }

            
                 $laporanData[] = [
                    'kategori' => $kategori->nama,
                    'terjual' => $terjualCount,
                    'gagal_terjual' => $gagalTerjualCount
                ];
                $totalTerjual += $terjualCount;
                $totalGagalTerjual += $gagalTerjualCount;
            
        }

        return view('owner.laporan.penjualan-kategori', [
            'laporanData' => $laporanData,
            'tahun' => $tahun,
            'tanggalCetak' => Carbon::now()->translatedFormat('d F Y'),
            'totalTerjual' => $totalTerjual,
            'totalGagalTerjual' => $totalGagalTerjual
        ]);
    }

    /**
     * Mengunduh PDF Laporan Penjualan per Kategori.
     * DIUBAH: Logika disamakan dengan method di atas.
     */
    public function downloadPenjualanKategoriPDF(Request $request)
    {
        $tahun = $request->input('tahun', Carbon::now()->year);

        $kategoriData = \App\Models\Kategoribarang::with([
            'barangTitipan.transaksiPenitipan',
            'barangTitipan.transaksiPembelian'
        ])->get();

        $laporanData = [];
        $totalTerjual = 0;
        $totalGagalTerjual = 0;

        foreach ($kategoriData as $kategori) {
            $terjualCount = 0;
            $gagalTerjualCount = 0;

            foreach ($kategori->barangTitipan as $barang) {
                $cocok = false;
                if ($barang->status == 'terjual' && $barang->transaksiPembelian) {
                    // Cek tahun dari tanggal pembayaran di transaksi pembelian
                    if (Carbon::parse($barang->transaksiPembelian->tanggal_pembayaran)->year == $tahun) {
                        $terjualCount++;
                        $cocok = true;
                    }
                } elseif ($barang->status == 'diambil kembali' && $barang->transaksiPenitipan) {
                    if (Carbon::parse($barang->transaksiPenitipan->tanggal_diambil)->year == $tahun) {
                        $gagalTerjualCount++;
                        $cocok = true;
                    }
                } elseif ($barang->status == 'didonasikan' && $barang->transaksiPenitipan) {
                    if (Carbon::parse($barang->transaksiPenitipan->tanggal_akhir)->year == $tahun) {
                        $terjualCount++;
                        $cocok = true;
                    }
                }
                elseif ($barang->status == 'disumbangkan' && $barang->transaksiPenitipan) {
                    if (Carbon::parse($barang->transaksiPenitipan->tanggal_akhir)->year == $tahun) {
                        $terjualCount++;
                        $cocok = true;
                    }
                }
                elseif ($barang->status == 'tersedia' && $barang->transaksiPenitipan) {
                    if (Carbon::parse($barang->transaksiPenitipan->tanggal_akhir)->year == $tahun) {
                        $gagalTerjualCount++;
                        $cocok = true;
                    }
                }
            }

            
                 $laporanData[] = [
                    'kategori' => $kategori->nama,
                    'terjual' => $terjualCount,
                    'gagal_terjual' => $gagalTerjualCount
                ];
                $totalTerjual += $terjualCount;
                $totalGagalTerjual += $gagalTerjualCount;
            
        }
        
        $data = [
            'laporanData' => $laporanData,
            'tahun' => $tahun,
            'tanggalCetak' => Carbon::now()->translatedFormat('d F Y'),
            'totalTerjual' => $totalTerjual,
            'totalGagalTerjual' => $totalGagalTerjual
        ];

        $pdf = Pdf::loadView('owner.laporan.pdf.penjualan-kategori-pdf', $data);
        return $pdf->download('laporan-penjualan-kategori-'.$tahun.'.pdf');
    }

    /**
     * Menampilkan halaman Laporan Barang yang Masa Penitipannya Habis.
     */
    public function laporanBarangExpired()
    {
        // Ambil semua barang yang statusnya masih aktif dan berpotensi untuk expired.
        $semuaBarang = BarangTitipan::whereNotIn('status', ['terjual', 'diambil kembali', 'didonasikan'])
            ->with('penitip', 'transaksiPenitipan')
            ->get();

        $laporanData = [];
        $now = Carbon::now();

        foreach ($semuaBarang as $barang) {
            // Pastikan ada data transaksi penitipan untuk diproses
            if ($barang->transaksiPenitipan && $barang->transaksiPenitipan->tanggal_penitipan) {
                $tanggalMasuk = Carbon::parse($barang->transaksiPenitipan->tanggal_penitipan);
                $tanggalAkhir = $tanggalMasuk->copy()->addDays(30);

                // Cek apakah barang sudah melewati tanggal akhir (expired)
                if ($tanggalAkhir->isPast()) {
                    $batasAmbil = $tanggalAkhir->copy()->addDays(7);

                    $laporanData[] = [
                        'barang' => $barang,
                        'tanggal_masuk' => $tanggalMasuk,
                        'tanggal_akhir' => $tanggalAkhir,
                        'batas_ambil' => $batasAmbil,
                    ];
                }
            }
        }

        return view('owner.laporan.barang-expired', [
            'laporanData' => $laporanData,
            'tanggalCetak' => $now->translatedFormat('d F Y')
        ]);
    }

    /**
     * Mengunduh PDF Laporan Barang yang Masa Penitipannya Habis.
     * DIUBAH: Logika disamakan dengan method di atas.
     */
    public function downloadBarangExpiredPDF()
    {
        $semuaBarang = BarangTitipan::whereNotIn('status', ['terjual', 'diambil kembali', 'didonasikan'])
            ->with('penitip', 'transaksiPenitipan')
            ->get();

        $laporanData = [];
        $now = Carbon::now();

        foreach ($semuaBarang as $barang) {
            if ($barang->transaksiPenitipan && $barang->transaksiPenitipan->tanggal_penitipan) {
                $tanggalMasuk = Carbon::parse($barang->transaksiPenitipan->tanggal_penitipan);
                $tanggalAkhir = $tanggalMasuk->copy()->addDays(30);

                if ($tanggalAkhir->isPast()) {
                    $batasAmbil = $tanggalAkhir->copy()->addDays(7);
                    $laporanData[] = [
                        'barang' => $barang,
                        'tanggal_masuk' => $tanggalMasuk,
                        'tanggal_akhir' => $tanggalAkhir,
                        'batas_ambil' => $batasAmbil,
                    ];
                }
            }
        }
        
        $data = [
            'laporanData' => $laporanData,
            'tanggalCetak' => $now->translatedFormat('d F Y')
        ];

        $pdf = Pdf::loadView('owner.laporan.pdf.barang-expired-pdf', $data);
        return $pdf->download('laporan-barang-expired-'.$now->format('Y-m-d').'.pdf');
    }

    public function ownerDashboard()
    {
       
        return view('owner.dashboard');
    }
}