<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\TransaksiPembelian;
use App\Models\Komisi;
use App\Models\BarangTitipan; // Ensure imported
use App\Models\TransaksiPenitipan; // Ensure imported
use App\Models\Penitip; // Ensure imported
use App\Models\Pegawai; // Ensure imported
use App\Models\Pembeli;
use Carbon\Carbon;

class ProcessCommissions extends Command
{
    protected $signature = 'commissions:process';
    protected $description = 'Process commissions and points for paid transactions';

    public function handle()
    {
        Log::info('Starting commission and points processing via artisan command at ' . now('Asia/Jakarta')->toDateTimeString());

        try {
            $result = $this->processCommissionsAndPoints(); // Renamed for clarity

            $message = 'Processing complete. Check logs for details.';
            // Simplified result handling for Artisan command
            if (is_array($result) && isset($result['message'])) {
                $message = $result['message'];
            }

            $this->info($message);
            Log::info('Commission and points processing completed via artisan command.', ['summary' => $message]);
        } catch (\Exception $e) {
            Log::error('Critical error in ProcessCommissions handle method: ' . $e->getMessage(), [
                'trace' => substr($e->getTraceAsString(), 0, 1000),
            ]);
            $this->error('Critical error occurred: ' . $e->getMessage());
            return 1; // Error exit code
        }

        return 0; // Success exit code
    }

    protected function processCommissionsAndPoints()
    {
        Log::info('Fetching paid transactions that may need commission processing...');
        
        // Fetch transactions that are paid and have items.
        // The condition to check for unprocessed commissions will be inside the item loop.
        $transaksiPembelians = TransaksiPembelian::whereNotNull('tanggal_pembayaran')
            ->whereHas('barangTitipan') // Ensure there are items to process
            ->with([
                'barangTitipan.penitip',
                'barangTitipan.komisi', // Eager load existing commissions for check
                'barangTitipan.transaksiPenitipan.pegawai',
                'pembeli',
            ])
            ->get();

        if ($transaksiPembelians->isEmpty()) {
            $message = 'No paid transactions with items found for commission processing.';
            Log::info($message);
            return ['message' => $message, 'status' => 200];
        }

        Log::info("Found {$transaksiPembelians->count()} paid transactions with items to check for commission.");
        $itemsProcessedForCommission = 0;
        $transactionsWithErrors = 0;

        foreach ($transaksiPembelians as $transaksi) {
            $atLeastOneItemProcessedInThisTransaction = false;
            Log::info("Checking TransaksiPembelian ID: {$transaksi->id_pembelian}");

            // Points for pembeli are processed once per transaction
            $pembeli = $transaksi->pembeli;
            $pointsProcessedForThisBuyer = false; // Flag to ensure points are added once

            DB::beginTransaction();
            try {
                if ($transaksi->barangTitipan->isNotEmpty()) {
                    foreach ($transaksi->barangTitipan as $barang) { // $barang is a single BarangTitipan item
                        if ($barang->komisi) { // Check if commission for THIS item already exists
                            Log::info("Commission for item {$barang->kode_barang} in TXN #{$transaksi->id_pembelian} already processed. Skipping.");
                            continue;
                        }

                        Log::info("Processing commission for item {$barang->kode_barang} in TXN #{$transaksi->id_pembelian}.");

                        $transaksiOriginalPenitipan = $barang->transaksiPenitipan;
                        $penitip = $barang->penitip;

                        if (!$penitip) {
                            Log::error("Penitip not found for BarangTitipan {$barang->kode_barang}. Skipping commission for this item.");
                            continue;
                        }

                        $pegawaiHunter = null;
                        $id_hunter_pegawai = null;
                        if ($transaksiOriginalPenitipan && $transaksiOriginalPenitipan->pegawai && $transaksiOriginalPenitipan->pegawai->id_jabatan == 6) { // Hunter
                            $pegawaiHunter = $transaksiOriginalPenitipan->pegawai;
                            $id_hunter_pegawai = $pegawaiHunter->id_pegawai;
                        }
                        
                        // Use item's price for commission calculation
                        $hargaJual = $barang->harga;
                        $komisi_hunter = 0;
                        $komisi_mart_final = 0;
                        $bonus_penitip_cepat = 0;

                        $persentaseKomisiMart = $barang->perpanjangan ? 0.30 : 0.20;
                        if ($pegawaiHunter) { // If hunter involved, mart's share might be less
                             $persentaseKomisiMart = $barang->perpanjangan ? 0.25 : 0.15;
                             $komisi_hunter = $hargaJual * 0.05;
                        }
                        $komisi_mart_final = $hargaJual * $persentaseKomisiMart;

                        if ($barang->terjual_cepat == 1) { 
                            $bonus_penitip_cepat = $komisi_mart_final * 0.10; 
                            Log::info("Bonus penitip cepat applied for item {$barang->kode_barang}.");
                        }

                        $penghasilan_kotor_penitip = $hargaJual - $komisi_mart_final - $komisi_hunter;
                        $komisi_penitip_final = $penghasilan_kotor_penitip + $bonus_penitip_cepat;

                        Komisi::create([
                            'kode_barang' => $barang->kode_barang,
                            'id_pegawai' => $id_hunter_pegawai,
                            'id_penitip' => $penitip->id_penitip,
                            'komisi_hunter' => round($komisi_hunter, 2),
                            'komisi_mart' => round($komisi_mart_final, 2),
                            'komisi_penitip' => round($komisi_penitip_final, 2),
                            'bonus' => round($bonus_penitip_cepat, 2),
                        ]);

                        $penitip->saldo = ($penitip->saldo ?? 0) + round($komisi_penitip_final, 2);
                        // Update penitip's rating and jumlah_jual (as per PegawaiController logic)
                        $ratingLama = $penitip->rating ?? 0;
                        $jumlahJualLama = $penitip->jumlah_jual ?? 0;
                        $ratingUntukPenjualanIni = 4; // Default rating
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
                        
                        $itemsProcessedForCommission++;
                        $atLeastOneItemProcessedInThisTransaction = true;
                        Log::info("Successfully processed commission for item {$barang->kode_barang} in TXN #{$transaksi->id_pembelian}. Penitip {$penitip->id_penitip} saldo/rating updated.");
                    } // End foreach item
                }

                // Process points for Pembeli if any item was processed and points not yet given for this transaction
                // This assumes poin_loyalitas for a buyer is based on the total transaction, not per item commission.
                if ($atLeastOneItemProcessedInThisTransaction && $pembeli && !$pointsProcessedForThisBuyer) {
                    // Check if points were already awarded for this transaction, perhaps by checking a flag on $transaksi or $pembeli
                    // For simplicity, we assume if we are here, points need to be added.
                    // A more robust check might involve seeing if $transaksi->poin_diperoleh was already set.
                    
                    $totalAkhir = $transaksi->total_akhir;
                    $basePoints = floor($totalAkhir / 10000);
                    $bonusPoints = $totalAkhir > 500000 ? floor($basePoints * 0.20) : 0;
                    $totalPoints = $basePoints + $bonusPoints;
                    
                    if ($totalPoints > 0) {
                        $pembeli->poin_loyalitas = ($pembeli->poin_loyalitas ?? 0) + $totalPoints;
                        $pembeli->save();
                        // Optionally set $transaksi->poin_diperoleh = $totalPoints; and $transaksi->save();
                        Log::info("Awarded {$totalPoints} points to Pembeli ID: {$pembeli->id_pembeli} for TXN #{$transaksi->id_pembelian}.");
                        $pointsProcessedForThisBuyer = true;
                    }
                } elseif (!$pembeli && $transaksi->id_pembeli) {
                    Log::warning("Pembeli relation not loaded or Pembeli not found for TXN #{$transaksi->id_pembelian}. Points skipped.");
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $transactionsWithErrors++;
                Log::error("Error processing commissions for TXN #{$transaksi->id_pembelian}: " . $e->getMessage(), [
                    'trace' => substr($e->getTraceAsString(), 0, 1000)
                ]);
            }
        } // End foreach transaction

        $message = "Commission and points processing command finished. Items processed for commission: {$itemsProcessedForCommission}. Transactions with errors: {$transactionsWithErrors}.";
        Log::info($message);
        return ['message' => $message, 'status' => 200];
    }
}