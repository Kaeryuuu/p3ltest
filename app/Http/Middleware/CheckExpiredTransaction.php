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
        if ($transaksi && $transaksi->status === 'akan diambil' && $transaksi->metode_pengiriman === 'pickup' && $transaksi->tanggal_pengambilan) {
            $expirationTime = Carbon::parse($transaksi->tanggal_pengambilan, 'Asia/Jakarta')->addHours(48);
            if (now('Asia/Jakarta')->greaterThanOrEqualTo($expirationTime)) {
                $transaksi->status = 'hangus';
                $transaksi->save();

                // Handle barangTitipan as a collection
                foreach ($transaksi->barangTitipan as $barang) {
                    $barang->status = 'didonasikan';
                    $barang->save();
                    Log::info("Item {$barang->kode_barang} from Transaction #{$transaksi->id_pembelian} set to status 'didonasikan'");
                }

                Log::info("Transaction #{$transaksi->id_pembelian} marked as Hangus");
            }
        }
    }

    return $next($request);
}
}