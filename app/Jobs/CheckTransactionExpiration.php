<?php

namespace App\Jobs;

use App\Models\TransaksiPembelian;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CheckTransactionExpiration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transaksiId;

    public function __construct($transaksiId)
    {
        $this->transaksiId = $transaksiId;
    }

    public function handle()
{
    $transaksi = TransaksiPembelian::with('barangTitipan')->find($this->transaksiId);
    if ($transaksi && $transaksi->status === 'akan diambil' && $transaksi->metode_pengiriman === 'pickup' && $transaksi->tanggal_pembayaran) {
        $expirationTime = Carbon::parse($transaksi->tanggal_pembayaran, 'Asia/Jakarta')->addHours(48);
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
}