<?php

namespace App\Http\Middleware;

use App\Models\TransaksiPembelian;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CheckExpiredTransaction
{
    public function handle(Request $request, Closure $next)
    {
        $id_pembelian = $request->route('id_pembelian');
        if ($id_pembelian) {
            $transaksi = TransaksiPembelian::with('barangTitipan')->find($id_pembelian);
            if ($transaksi && $transaksi->status === 'akan diambil' && $transaksi->metode_pengiriman === 'pickup' && $transaksi->tanggal_pembayaran) {
                $expirationTime = Carbon::parse($transaksi->tanggal_pembayaran, 'Asia/Jakarta')->addHours(48);
                if (now('Asia/Jakarta')->greaterThanOrEqualTo($expirationTime)) {
                    $transaksi->status = 'hangus';
                    $transaksi->save();

                    $transaksi->barangTitipan->status = 'didonasikan';
                    $transaksi->barangTitipan->save();

                    Log::info("Transaction #{$transaksi->id_pembelian} marked as Hangus, item {$transaksi->barangTitipan->kode_barang} set to barang untuk donasi");
                }
            }
        }

        return $next($request);
    }
}