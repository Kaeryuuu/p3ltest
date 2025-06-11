<?php

namespace App\Console\Commands;

use App\Models\TransaksiPembelian;
use App\Models\BarangTitipan; // Make sure BarangTitipan is imported
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Import DB for transactions

class CheckExpiredTransactions extends Command
{
    protected $signature = 'transactions:check-expired';
    protected $description = 'Check for expired pickup transactions and update statuses';

    public function handle()
    {
        Log::info('Running CheckExpiredTransactions at ' . now('Asia/Jakarta')->toDateTimeString());

        $expiredTransactions = TransaksiPembelian::where('status', 'akan diambil')
            ->whereNotNull('tanggal_pengambilan')
            ->where('tanggal_pengambilan', '<=', now('Asia/Jakarta')->subDays(2))
            ->with('barangTitipan.penitip', 'pembeli') // Eager load for notifications
            ->get();

        if ($expiredTransactions->isEmpty()) {
            Log::info('No expired transactions found to process.');
            $this->info('No expired transactions found.');
            return 0;
        }

        foreach ($expiredTransactions as $transaksi) {
            DB::beginTransaction();
            try {
                $transaksi->status = 'hangus';
                $transaksi->save();
                Log::info("Transaction #{$transaksi->id_pembelian} marked as Hangus.");

                if ($transaksi->barangTitipan->isNotEmpty()) {
                    foreach ($transaksi->barangTitipan as $barang) { // Iterate through each item
                        $barang->status = 'didonasikan'; // Or another appropriate status
                        $barang->save();
                        Log::info("Item {$barang->kode_barang} (from Transaction #{$transaksi->id_pembelian}) set to status 'didonasikan'.");
                    }
                } else {
                    Log::warning("Transaction #{$transaksi->id_pembelian} has no associated items to update.");
                }

                // Notify pembeli and penitip (penitip notification might need to be per item if relevant)
                $this->sendExpirationNotification($transaksi);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error processing expired Transaction #{$transaksi->id_pembelian}: " . $e->getMessage(), [
                    'trace' => substr($e->getTraceAsString(), 0, 500)
                ]);
            }
        }

        $this->info('Expired transactions processed successfully.');
        return 0;
    }

    protected function sendExpirationNotification($transaksi)
    {
        $pembeli = $transaksi->pembeli;
        if ($pembeli) {
            // Example: Use Laravel Notification for Pembeli
            // \Notification::send($pembeli, new TransactionExpiredNotificationForBuyer($transaksi));
            Log::info("Attempting to send expiration notification to Pembeli ID: {$pembeli->id_pembeli} for Transaction #{$transaksi->id_pembelian}");
        }

        // For Penitip, notification might be per item, or one summary.
        // If per item, this logic would need to be inside the item loop or iterate $transaksi->barangTitipan here.
        if ($transaksi->barangTitipan->isNotEmpty()) {
            foreach ($transaksi->barangTitipan as $barang) {
                $penitip = $barang->penitip;
                if ($penitip) {
                    // Example: Use Laravel Notification for Penitip for each item
                    // \Notification::send($penitip, new ItemFromExpiredTransactionNotificationForPenitip($transaksi, $barang));
                    Log::info("Attempting to send item expiration (from Transaction #{$transaksi->id_pembelian}) notification to Penitip ID: {$penitip->id_penitip} for Item {$barang->kode_barang}");
                }
            }
        }
    }
}