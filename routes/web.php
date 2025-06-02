<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\PegawaiController;
use App\Http\Middleware\CheckExpiredTransaction; // Pastikan ini adalah kelas Middleware yang valid
use App\Http\Controllers\RequestDonasiController;
// Jika TransaksiPembelianController benar-benar digunakan untuk route di bawah, pastikan ada
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
    Route::get('/profile', [PenitipController::class, 'barangTitipanIndex'])->name('profile'); // Mungkin lebih baik ke dashboard penitip?
    Route::get('/penitip/dashboard', [PenitipController::class, 'barangTitipanIndex'])->name('penitip.dashboard');

    Route::prefix('penitip/barang-titipan')->name('penitip.barang-titipan.')->group(function () {
        Route::get('/manage', [PenitipController::class, 'barangTitipanManage'])->name('manage');
        Route::post('/{kode_barang}/extend', [PenitipController::class, 'barangTitipanExtend'])->name('extend');
        Route::post('/{kode_barang}/confirm-pickup', [PenitipController::class, 'barangTitipanConfirmPickup'])->name('confirm-pickup');
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
    // Dashboards by role (Consider using a single dashboard with role-based content)
    Route::get('/owner/dashboard', function () { return view('dashboards.owner'); })->name('owner.dashboard');
    Route::get('/admin/dashboard', function () { return view('dashboards.admin'); })->name('admin.dashboard');
    Route::get('/cs/dashboard', function () { return view('dashboards.cs'); })->name('cs.dashboard');
    Route::get('/gudang/dashboard', function () { return view('dashboards.gudang'); })->name('gudang.dashboard');
    Route::get('/kurir/dashboard', function () { return view('dashboards.kurir'); })->name('kurir.dashboard'); // Nama route disesuaikan
    Route::get('/hunter/dashboard', function () { return view('dashboards.hunter'); })->name('hunter.dashboard');

    // CS - Penitip Management
    Route::prefix('cs/penitip')->name('cs.penitip.')->group(function () {
        Route::get('/', [PenitipController::class, 'index'])->name('index');
        Route::get('/create', [PenitipController::class, 'create'])->name('create');
        Route::post('/', [PenitipController::class, 'store'])->name('store');
        Route::get('/{id_penitip}/edit', [PenitipController::class, 'edit'])->name('edit');
        Route::put('/{id_penitip}', [PenitipController::class, 'update'])->name('update');
        Route::patch('/{id_penitip}/deactivate', [PenitipController::class, 'deactivate'])->name('deactivate');
        Route::patch('/{id_penitip}/activate', [PenitipController::class, 'activate'])->name('activate');
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
            ;
        Route::post('/{id_pembelian}/schedule-delivery', [PegawaiController::class, 'scheduleDelivery'])->name('schedule-delivery');
        Route::post('/{id_pembelian}/confirm-pickup', [PegawaiController::class, 'confirmPickup'])->name('confirm-pickup');
        Route::post('/{id_pembelian}/schedule-pickup', [PegawaiController::class, 'schedulePickup'])->name('schedule-pickup');
        Route::get('/{id_pembelian}/print-invoice', [PegawaiController::class, 'generateInvoicePDF'])->name('print-invoice');
    });

    // Route::prefix('gudang/penitipan')->name('gudang.penitipan.')->group(function () {
       
    //     Route::get('/{id_penitipan}/print-nota', [PegawaiController::class, 'generateNotaPenitipanPDF'])->name('print-nota');
    // });


    // Rute untuk memproses komisi melalui web (TINJAU KEMBALI KEPERLUANNYA)
    // Pastikan TransaksiPembelianController dan methodnya ada jika route ini dipertahankan.
    // Jika TransaksiPembelianController.php adalah duplikat PegawaiController.php, ini tidak akan berfungsi.
    Route::get('/transaksi-pembelian/process-commissions', [TransaksiPembelianController::class, 'processCommissionsAndPoints'])
        ->name('transaksi-pembelian.process-commissions');
    // Komentari dulu jika tidak yakin atau jika Artisan command lebih diutamakan.
});
