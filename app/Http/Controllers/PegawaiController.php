<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\BarangTitipan;
use App\Models\TransaksiPenitipan;
use App\Models\TransaksiPembelian;
use App\Models\Komisi;
use App\Models\Penitip;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF; // Tambahkan \Pdf



class PegawaiController extends Controller
{
    // ... (constructor and other methods like index, store, show, update, destroy, barangTitipanIndex, showBarangTitipanDetail, recordPickup remain largely the same as previously corrected) ...
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::guard('pegawai')->check() || !Auth::guard('pegawai')->user()->jabatan) {
                abort(403, 'Akses ditolak. Informasi pegawai tidak lengkap.');
            }
            if (Auth::guard('pegawai')->user()->jabatan->id_jabatan !== 4) { // Pastikan ID Jabatan Gudang adalah 4
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
        $barangTitipan = BarangTitipan::whereIn('status', ['tersedia', 'didonasikan', 'akan diambil', 'sudah diambil', 'terjual', 'hangus'])
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
        
        $transaksiDataPenitipan = $barang->transaksiPenitipan; // Correctly uses the belongsTo relationship
        
        if (!$transaksiDataPenitipan) {
            return redirect()->route('gudang.barang-titipan.detail', $barang->kode_barang)
                             ->with('error', 'Data transaksi penitipan yang relevan tidak ditemukan untuk barang "' . $barang->nama . '".');
        }
            
        $transaksiDataPenitipan->tanggal_diambil = now();
        $transaksiDataPenitipan->id_pegawai = Auth::guard('pegawai')->user()->id_pegawai;
        $transaksiDataPenitipan->save();
        
        $barang->status = 'sudah diambil';
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
        // TransaksiPembelian->barangTitipan() is now hasMany.
        // The logic below needs to iterate through each barangTitipan in the collection.
        $transaksi = TransaksiPembelian::with([
            'barangTitipan.fotos',
            'barangTitipan.komisi',
            'barangTitipan.penitip',
            'barangTitipan.transaksiPenitipan.pegawai',
            'pembeli',
            'kurir'
        ])->findOrFail($id_pembelian);

        if ($transaksi->tanggal_pembayaran && $transaksi->barangTitipan->isNotEmpty()) {
    // Filter item yang komisinya belum diproses
            $itemsToProcessCommissionFor = $transaksi->barangTitipan->filter(function ($barang) {
                return !$barang->komisi; // $barang->komisi akan null jika belum ada komisi terkait
            });

        if ($itemsToProcessCommissionFor->isNotEmpty()) {
            Log::info("[PegawaiController][transaksiDetail] Transaksi ID {$id_pembelian}: Pembayaran ada. Ditemukan {$itemsToProcessCommissionFor->count()} item(s) yang komisinya belum diproses. Memulai proses.");
            DB::beginTransaction();
            try {
                // Iterasi HANYA pada item yang komisinya belum diproses
                foreach ($itemsToProcessCommissionFor as $barang) {
                    // Logika perhitungan komisi Anda untuk $barang...
                    // (Contoh: ambil $penitip, $pembeli, $transaksiOriginalPenitipan, $pegawaiHunter, dll.)
                    // ...
                    Log::info("[PegawaiController][transaksiDetail] Memproses komisi untuk barang {$barang->kode_barang} dalam Transaksi ID {$id_pembelian}.");

                    $penitip = $barang->penitip;
                    // $pembeli sudah diambil dari $transaksi->pembeli sebelumnya (di luar loop barang)
                    $transaksiOriginalPenitipan = $barang->transaksiPenitipan;
                    $pegawaiHunter = null;
                    $id_hunter = null; // pastikan di-reset untuk setiap barang

                    if ($transaksiOriginalPenitipan && $transaksiOriginalPenitipan->pegawai) {
                        $pegawaiHunter = $transaksiOriginalPenitipan->pegawai;
                    }

                    if (!$penitip) {
                        Log::error("Penitip tidak ditemukan untuk Barang {$barang->kode_barang} dalam Transaksi ID {$id_pembelian}. Komisi untuk item ini dilewati.");
                        // Jika ingin melanjutkan item berikutnya meskipun ada error pada satu item:
                        // continue; 
                        // Jika ingin menggagalkan seluruh transaksi jika satu item error:
                        throw new \Exception("Penitip tidak ditemukan untuk Barang {$barang->kode_barang}");
                    }
                    
                    $hargaJual = $barang->harga; // Pastikan ini harga jual item
                    $komisi_hunter = 0;
                    $komisi_mart_final = 0;
                    $bonus_penitip_cepat = 0;

                    $persentaseKomisiMart = $barang->perpanjangan ? 0.30 : 0.20;
                    if ($pegawaiHunter && $pegawaiHunter->id_jabatan == 6) { // ID Jabatan Hunter
                        $komisi_hunter = $hargaJual * 0.05;
                        $persentaseKomisiMart = $barang->perpanjangan ? 0.25 : 0.15; 
                        $id_hunter = $pegawaiHunter->id_pegawai;
                    }
                    $komisi_mart_final = $hargaJual * $persentaseKomisiMart;

                    if (($barang->terjual_cepat == 1)) {
                        $bonus_penitip_cepat = $komisi_mart_final * 0.10;
                        Log::info("[PegawaiController][transaksiDetail] Bonus penitip cepat diterapkan untuk barang {$barang->kode_barang}.");
                    }

                    $penghasilan_kotor_penitip = $hargaJual - $komisi_mart_final - $komisi_hunter;
                    $komisi_penitip_final = $penghasilan_kotor_penitip + $bonus_penitip_cepat;

                    // Update saldo, jumlah_jual, dan rating Penitip (logika ini tetap per barang)
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
                    $barang->load('komisi'); // Muat ulang relasi komisi untuk item ini
                }

                // Poin loyalitas pembeli (dihitung sekali per transaksi, setelah semua item diproses)
                $pembeli = $transaksi->pembeli; // Ambil dari transaksi utama
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
                $transaksi->load('barangTitipan.komisi'); // Muat ulang semua relasi komisi untuk view

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("[PegawaiController][transaksiDetail] Gagal memproses komisi saat akses detail transaksi ID {$id_pembelian}: " . $e->getMessage(), [
                    'trace' => substr($e->getTraceAsString(), 0, 1000)
                ]);
            }
        } elseif ($transaksi->tanggal_pembayaran && $transaksi->barangTitipan->isNotEmpty() && $itemsToProcessCommissionFor->isEmpty()) {
            Log::info("[PegawaiController][transaksiDetail] Transaksi ID {$id_pembelian}: Pembayaran ada, namun semua item sudah memiliki komisi. Skip proses komisi.");
        } else {
            if (!$transaksi->tanggal_pembayaran) {
                Log::info("[PegawaiController][transaksiDetail] Transaksi ID {$id_pembelian}: Pembayaran belum ada, skip proses komisi.");
            } elseif ($transaksi->barangTitipan->isEmpty()) {
                Log::warning("[PegawaiController][transaksiDetail] Transaksi ID {$id_pembelian}: Tidak ada BarangTitipan terkait, skip proses komisi.");
            }
        }
    }

        // The view gudang.transaksi.detail now needs to be able to display multiple items if $transaksi->barangTitipan is a collection.
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

        // If TransaksiPembelian hasMany BarangTitipan, you need to update status for all items.
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
        $transaksi->status = 'akan f'; 
        $transaksi->save();
        return redirect()->route('gudang.transaksi.detail', $id_pembelian)->with('success', 'Pengambilan oleh pembeli berhasil dijadwalkan.'); 
    }

    public function generateInvoicePDF($id_pembelian)
{
    // Eager load semua relasi yang dibutuhkan untuk nota
    $transaksi = TransaksiPembelian::with([
        'pembeli',
        'kurir', // Jika ada dan relevan untuk nota
        'barangTitipan.penitip', // Ambil semua barang dalam transaksi ini dan info penitipnya
        'barangTitipan.fotos' // Jika perlu menampilkan foto barang di nota
    ])->findOrFail($id_pembelian);

    // Tentukan view berdasarkan metode pengiriman, atau Anda bisa memiliki satu view universal
    // Jika ada perbedaan signifikan antara nota pickup dan nota kirim
    $viewName = ''; // Contoh nama view universal
    if ($transaksi->metode_pengiriman === 'dikirim') {
        $viewName = 'invoice-kurir'; // Merujuk ke resources/views/invoice/invoice-kurir.blade.php
    } elseif ($transaksi->metode_pengiriman === 'pickup') {
        $viewName = 'invoice-pickup'; // Merujuk ke resources/views/invoice/invoice-pickup.blade.php
    } else {
        // Fallback atau error jika metode pengiriman tidak dikenal/tidak sesuai untuk invoice
        // Untuk saat ini, kita bisa fallback ke salah satu atau throw error
        // Atau Anda bisa membuat view default jika diperlukan.
        // Misalnya, jika hanya ada dua jenis ini, kondisi else mungkin tidak tercapai jika data valid.
        Log::error("Metode pengiriman tidak valid ('{$transaksi->metode_pengiriman}') untuk generate invoice Transaksi ID: {$id_pembelian}.");
        // Anda bisa redirect back dengan error atau menggunakan view default jika ada
        // return redirect()->back()->with('error', 'Tidak dapat membuat invoice untuk metode pengiriman ini.');
        // Untuk tujuan contoh, jika tidak ada, kita bisa set default atau biarkan kosong agar error
        // (yang akan ditangkap oleh Laravel sebagai view not found jika $viewName kosong dan tetap dipakai).
        // Namun lebih baik memiliki handling yang jelas.
        // Untuk sementara kita bisa asumsikan salah satu dari dua itu.
        // Jika $viewName tetap kosong, PDF::loadView akan error.
        // Sebaiknya ada validasi di sini jika metode pengiriman bisa lainnya.
        // Untuk sekarang, kita lanjutkan dengan asumsi salah satu dari dua itu.
        if (empty($viewName)) {
             // Jika $transaksi->metode_pengiriman bukan 'dikirim' atau 'pickup', maka $viewName akan kosong.
             // Anda perlu memutuskan apa yang harus dilakukan di sini.
             // Misalnya, menggunakan view default atau menampilkan pesan error.
             // Untuk contoh, saya akan redirect dengan error jika tidak ada view yang cocok:
             return redirect()->route('gudang.transaksi.detail', $id_pembelian)
                            ->with('error', 'Jenis invoice tidak didukung untuk metode pengiriman ini.');
        }
    }

    // Data yang akan dikirim ke view PDF
    $data = [
        'transaksi' => $transaksi,
        // Tambahkan data lain jika diperlukan, misalnya informasi perusahaan/toko
        'nama_toko' => 'ReUseMart',
        'alamat_toko' => 'Jl. ReUse No. 1, Yogyakarta',
        'telepon_toko' => '0812-3456-7890',
    ];

    // Load view dan data ke PDF
    $pdf = PDF::loadView($viewName, $data);

    // Atur nama file PDF yang akan di-download
    $fileName = "nota-penjualan-{$transaksi->id_pembelian}-{$transaksi->no_nota_pembelian}.pdf";

    // Opsi 1: Tampilkan PDF di browser
    // return $pdf->stream($fileName);

    // Opsi 2: Langsung download PDF
    return $pdf->download($fileName);
}
}