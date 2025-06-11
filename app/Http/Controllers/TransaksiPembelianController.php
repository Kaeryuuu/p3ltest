<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPembelian;
use App\Models\BarangTitipan;
use App\Models\Komisi;
use App\Models\Penitip;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TransaksiPembelianController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::guard('pegawai')->check() || !Auth::guard('pegawai')->user()->jabatan) {
                abort(403, 'Akses ditolak. Informasi pegawai tidak lengkap.');
            }
            if (Auth::guard('pegawai')->user()->jabatan->id_jabatan !== 4) {
                abort(403, 'Akses ditolak. Hanya Pegawai Gudang yang dapat mengakses halaman ini.');
            }
            return $next($request);
        })->only([
            'transaksiIndex',
            'transaksiDetail',
            'scheduleDelivery',
            'confirmPickup',
            'schedulePickup',
        ]);
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
            DB::beginTransaction();
            try {
                foreach ($transaksi->barangTitipan as $barang) {
                    if ($barang->komisi->isEmpty()) {
                        Log::info("[TransaksiPembelianController][transaksiDetail] Transaksi ID {$id_pembelian}: Komisi untuk barang {$barang->kode_barang} belum diproses.");
                        $penitip = $barang->penitip;
                        $pembeli = $transaksi->pembeli;
                        $transaksiDataPenitipan = $barang->transaksiPenitipan;
                        $pegawaiHunter = $transaksiDataPenitipan ? $transaksiDataPenitipan->pegawai : null;

                        if (!$penitip) {
                            throw new \Exception("Penitip tidak ditemukan untuk Barang {$barang->kode_barang}");
                        }

                        $hargaJual = $barang->harga;
                        $komisi_hunter = 0;
                        $komisi_mart_final = 0;
                        $bonus_penitip_cepat = 0;
                        $id_hunter = null;

                        $persentaseKomisiMart = $barang->perpanjangan ? 0.30 : 0.20;
                        $komisi_mart_awal = $hargaJual * $persentaseKomisiMart;
                        $komisi_mart_final = $komisi_mart_awal;

                        if ($pegawaiHunter && $pegawaiHunter->id_jabatan == 6) {
                            $komisi_hunter = $hargaJual * 0.05;
                            $komisi_mart_final = $hargaJual * ($barang->perpanjangan ? 0.25 : 0.15);
                            $id_hunter = $pegawaiHunter->id_pegawai;
                        }

                        if ($barang->terjual_cepat) {
                            $bonus_penitip_cepat = $komisi_mart_final * 0.10;
                            $komisi_mart_final += $bonus_penitip_cepat;
                        }

                        $penghasilan_kotor_penitip = $hargaJual - $komisi_mart_final - $komisi_hunter;

                        $komisi_penitip_final = $penghasilan_kotor_penitip + $bonus_penitip_cepat;

                        $penitip->saldo = ($penitip->saldo ?? 0) + round($komisi_penitip_final, 2);
                        $penitip->jumlah_jual = ($penitip->jumlah_jual ?? 0) + 1;
                        $penitip->save();

                        if ($pembeli) {
                            $totalAkhir = $transaksi->total_akhir;
                            $poinDasar = floor($totalAkhir / 10000);
                            $bonusPoinTambahan = ($totalAkhir > 500000) ? floor($poinDasar * 0.20) : 0;
                            $totalPoinDiperoleh = $poinDasar + $bonusPoinTambahan;
                            $pembeli->poin_loyalitas = ($pembeli->poin_loyalitas ?? 0) + $totalPoinDiperoleh;
                            $pembeli->save();
                        }

                        Komisi::create([
                            'kode_barang' => $barang->kode_barang,
                            'id_pegawai' => $id_hunter,
                            'id_penitip' => $penitip->id_penitip,
                            'komisi_hunter' => round($komisi_hunter, 2),
                            'komisi_mart' => round($komisi_mart_final, 2),
                            'komisi_penitip' => round($komisi_penitip_final, 2),
                            'bonus' => round($bonus_penitip_cepat, 2),
                        ]);
                    }
                }
                DB::commit();
                $transaksi->barangTitipan->load('komisi');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("[TransaksiPembelianController][transaksiDetail] Gagal memproses komisi: " . $e->getMessage());
            }
        }

        return view('gudang.transaksi.detail', compact('transaksi'));
    }

    public function scheduleDelivery(Request $request, $id_pembelian)
    {
        $transaksi = TransaksiPembelian::findOrFail($id_pembelian);
        if ($transaksi->status !== 'sedang dikemas' || $transaksi->metode_pengiriman !== 'dikirim') {
            return redirect()->route('gudang.transaksi.detail', $id_pembelian)->with('error', 'Pengiriman tidak dapat dijadwalkan.');
        }
        $validated = $request->validate([
            'tanggal_pengiriman' => [
                'required', 'date', 'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    $date = Carbon::parse($value, 'Asia/Jakarta')->startOfDay();
                    $today = Carbon::now('Asia/Jakarta')->startOfDay();
                    if ($date->isSameDay($today) && Carbon::now('Asia/Jakarta')->hour >= 14) {
                        $fail('Pengiriman setelah jam 4 sore tidak dapat dijadwalkan untuk hari ini.');
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
        $transaksi = TransaksiPembelian::with('barangTitipan')->findOrFail($id_pembelian);
        if ($transaksi->status !== 'akan diambil' || $transaksi->metode_pengiriman !== 'pickup') {
            return redirect()->route('gudang.transaksi.detail', $id_pembelian)->with('error', 'Konfirmasi pengambilan tidak dapat dilakukan.');
        }
        $transaksi->status = 'selesai';
        $transaksi->tanggal_pengambilan = now();
        $transaksi->save();

        foreach ($transaksi->barangTitipan as $barang) {
            $barang->status = 'terjual';
            $barang->save();
        }

        return redirect()->route('gudang.transaksi.detail', $id_pembelian)->with('success', 'Pengambilan barang berhasil dikonfirmasi.');
    }

    public function schedulePickup(Request $request, $id_pembelian)
    {
        $transaksi = TransaksiPembelian::findOrFail($id_pembelian);
        if ($transaksi->status !== 'siap diambil' || $transaksi->metode_pengiriman !== 'pickup') {
            return redirect()->route('gudang.transaksi.detail', $id_pembelian)->with('error', 'Penjadwalan pengambilan tidak dapat dilakukan.');
        }
        $validated = $request->validate([
            'tanggal_pengambilan' => 'required|date|after_or_equal:today',
        ]);
        $transaksi->tanggal_pengambilan = $validated['tanggal_pengambilan'];
        $transaksi->status = 'akan diambil';
        $transaksi->save();
        return redirect()->route('gudang.transaksi.detail', $id_pembelian)->with('success', 'Pengambilan berhasil dijadwalkan.');
    }
}