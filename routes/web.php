<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\KategoriBarangController;
use App\Http\Middleware\CheckExpiredTransaction;
use App\Http\Controllers\RequestDonasiController;
use App\Http\Controllers\TransaksiPembelianController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return view('homepage');
})->name('homepage');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register/user', [PembeliController::class, 'registerUser'])->name('registerUser');
Route::post('/register/organisasi', [OrganisasiController::class, 'registerPost'])->name('registerPost');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// --- PENITIP ROUTES ---
Route::middleware(['auth:penitip'])->group(function () {
    Route::get('/profile', [PenitipController::class, 'barangTitipanIndex'])->name('profile');
    Route::get('/penitip/dashboard', [PenitipController::class, 'barangTitipanIndex'])->name('penitip.dashboard');

    Route::prefix('penitip/barang-titipan')->name('penitip.barang-titipan.')->group(function () {
        Route::get('/manage', [PenitipController::class, 'barangTitipanManage'])->name('manage');
        Route::post('/{kode_barang}/extend', [PenitipController::class, 'barangTitipanExtend'])->name('extend');
        Route::post('/{id_penitipan}/confirm-pickup', [PenitipController::class, 'barangTitipanConfirmPickup'])->name('confirm-pickup');
        Route::get('/{kode_barang}/detail', [PenitipController::class, 'barangTitipanShow'])->name('show');
    });
});

// --- PEMBELI ROUTES ---
Route::middleware(['auth:pembeli'])->group(function () {
    Route::get('/pembeli/dashboard', [PembeliController::class, 'dashboard'])->name('pembeli.dashboard');
    Route::get('/pembeli/transaksi/{id_pembelian}', [PembeliController::class, 'transactionDetail'])->name('pembeli.transaction.detail');
});

// --- ORGANISASI ROUTES ---
Route::middleware(['auth:organisasi'])->group(function () {
    Route::get('/organisasi/dashboard', function () {
        return view('dashboards.organisasi');
    })->name('organisasi.dashboard');

    Route::prefix('organisasi/request-donasi')->name('organisasi.request-donasi.')->group(function () {
        Route::get('/', [RequestDonasiController::class, 'index'])->name('index');
        Route::get('/create', [RequestDonasiController::class, 'create'])->name('create');
        Route::post('/', [RequestDonasiController::class, 'store'])->name('store');
        Route::get('/{id_request}/edit', [RequestDonasiController::class, 'edit'])->name('edit');
        Route::put('/{id_request}', [RequestDonasiController::class, 'update'])->name('update');
        Route::delete('/{id_request}', [RequestDonasiController::class, 'destroy'])->name('destroy');
    });
});

// --- PEGAWAI ROUTES ---
Route::middleware(['auth:pegawai'])->group(function () {
    // Dashboards by role
    // --- DIUBAH: Dashboard Owner kini menunjuk ke controller ---
    Route::get('/owner/dashboard', [PegawaiController::class, 'ownerDashboard'])->name('owner.dashboard');
    
    Route::get('/admin/dashboard', function () { return view('dashboards.admin'); })->name('admin.dashboard');
    Route::get('/cs/dashboard', function () { return view('dashboards.cs'); })->name('cs.dashboard');
    Route::get('/gudang/dashboard', function () { return view('dashboards.gudang'); })->name('gudang.dashboard');
    Route::get('/kurir/dashboard', function () { return view('dashboards.kurir'); })->name('kurir.dashboard');
    Route::get('/hunter/dashboard', function () { return view('dashboards.hunter'); })->name('hunter.dashboard');

    // --- DITAMBAHKAN: Rute Laporan untuk Owner ---
    Route::prefix('owner/laporan')->name('owner.laporan.')->group(function () {
        Route::get('/penjualan-kategori', [PegawaiController::class, 'laporanPenjualanKategori'])->name('penjualan-kategori');
        Route::get('/penjualan-kategori/pdf', [PegawaiController::class, 'downloadPenjualanKategoriPDF'])->name('penjualan-kategori.pdf');
        Route::get('/barang-expired', [PegawaiController::class, 'laporanBarangExpired'])->name('barang-expired');
        Route::get('/barang-expired/pdf', [PegawaiController::class, 'downloadBarangExpiredPDF'])->name('barang-expired.pdf');
    });

    // CS - Penitip Management
    Route::prefix('cs/penitip')->name('cs.penitip.')->group(function () {
        Route::get('/', [PenitipController::class, 'index'])->name('index');
        Route::get('/create', [PenitipController::class, 'create'])->name('create');
        Route::post('/', [PenitipController::class, 'store'])->name('store');
        Route::get('/{id_penitip}/edit', [PenitipController::class, 'edit'])->name('edit');
        Route::put('/{id_penitip}', [PenitipController::class, 'update'])->name('update');
        Route::patch('/{id_penitip}/deactivate', [PenitipController::class, 'deactivate'])->name('deactivate');
        Route::patch('/{id_penitip}/activate', [PenitipController::class, 'activate'])->name('activate');
        Route::get('/low-saldo', [PenitipController::class, 'lowSaldoPenitip'])->name('low-saldo');
    });

    // Gudang - Barang Titipan Management
    Route::prefix('gudang/barang-titipan')->name('gudang.barang-titipan.')->group(function () {
        Route::get('/', [PegawaiController::class, 'barangTitipanIndex'])->name('index');
        Route::post('/{kode_barang}/record-pickup', [PegawaiController::class, 'recordPickup'])->name('record-pickup');
        Route::get('/{kode_barang}/detail', [PegawaiController::class, 'showBarangTitipanDetail'])->name('detail');
    });

    // Gudang - Transaksi Pembelian Management
    Route::prefix('gudang/transaksi')->name('gudang.transaksi.')->group(function () {
        Route::get('/', [PegawaiController::class, 'transaksiIndex'])->name('index');
        Route::get('/{id_pembelian}', [PegawaiController::class, 'transaksiDetail'])
            ->name('detail')
            ->middleware(CheckExpiredTransaction::class);
        Route::post('/{id_pembelian}/schedule-delivery', [PegawaiController::class, 'scheduleDelivery'])->name('schedule-delivery');
        Route::post('/{id_pembelian}/confirm-pickup', [PegawaiController::class, 'confirmPickup'])->name('confirm-pickup');
        Route::post('/{id_pembelian}/schedule-pickup', [PegawaiController::class, 'schedulePickup'])->name('schedule-pickup');
        Route::get('/{id_pembelian}/print-invoice', [PegawaiController::class, 'generateInvoicePDF'])->name('print-invoice');
    });

    // --- DIPERBAIKI: Nama rute untuk owner transaksi kini benar ---
    Route::prefix('owner/transaksi')->name('owner.transaksi.')->group(function () {
        Route::get('/', [PegawaiController::class, 'transaksiIndex'])->name('index');
        Route::get('/{id_pembelian}', [PegawaiController::class, 'transaksiDetail'])
            ->name('detail')
            ->middleware(CheckExpiredTransaction::class);
        Route::post('/{id_pembelian}/schedule-delivery', [PegawaiController::class, 'scheduleDelivery'])->name('schedule-delivery');
        Route::post('/{id_pembelian}/confirm-pickup', [PegawaiController::class, 'confirmPickup'])->name('confirm-pickup');
        Route::post('/{id_pembelian}/schedule-pickup', [PegawaiController::class, 'schedulePickup'])->name('schedule-pickup');
        Route::get('/{id_pembelian}/print-invoice', [PegawaiController::class, 'generateInvoicePDF'])->name('print-invoice');
    });

    // Rute untuk memproses komisi melalui web (TINJAU KEMBALI KEPERLUANNYA)
    Route::get('/transaksi-pembelian/process-commissions', [TransaksiPembelianController::class, 'processCommissionsAndPoints'])
        ->name('transaksi-pembelian.process-commissions');
});