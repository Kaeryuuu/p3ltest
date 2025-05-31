<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\RequestDonasiController;
use Illuminate\Support\Facades\Auth; // Pastikan ini ada
use Illuminate\Support\Facades\Log; // Pastikan ini ada jika menggunakan Log di route closure

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
})->middleware(['auth'])->name('dashboard'); // Ini adalah middleware umum, mungkin perlu disesuaikan


Route::middleware(['auth:penitip'])->group(function () {
    Route::get('/profile', [PenitipController::class, 'barangTitipanIndex'])->name('profile'); // Mungkin bisa dihapus jika sama dengan dashboard
    Route::get('/penitip/dashboard', [PenitipController::class, 'barangTitipanIndex'])->name('penitip.dashboard');

    Route::prefix('penitip/barang-titipan')->name('penitip.barang-titipan.')->group(function () {
        // Route::get('/', [PenitipController::class, 'barangTitipanIndex'])->name('index'); // Mungkin redundan dengan manage
        // Route::get('/search', [PenitipController::class, 'barangTitipanSearch'])->name('search'); // Jika search terpisah
        
        Route::get('/manage', [PenitipController::class, 'barangTitipanManage'])->name('manage');
        Route::post('/{kode_barang}/extend', [PenitipController::class, 'barangTitipanExtend'])->name('extend');
        Route::post('/{kode_barang}/confirm-pickup', [PenitipController::class, 'barangTitipanConfirmPickup'])->name('confirm-pickup');
        Route::get('/{kode_barang}/detail', [PenitipController::class, 'barangTitipanShow'])->name('show'); // RUTE BARU UNTUK DETAIL
    });
});

Route::middleware(['auth:pembeli'])->group(function () {
    Route::get('/pembeli/dashboard', [PembeliController::class, 'dashboard'])->name('pembeli.dashboard');
    Route::get('/pembeli/transaksi/{id_pembelian}', [PembeliController::class, 'transactionDetail'])->name('pembeli.transaction.detail');
});

Route::middleware(['auth:organisasi'])->group(function () {
    Route::get('/organisasi/dashboard', function () {
        return view('dashboards.organisasi');
    })->name('organisasi.dashboard');

    Route::prefix('organisasi/request-donasi')->group(function () {
        Route::get('/', [RequestDonasiController::class, 'index'])->name('organisasi.request-donasi.index');
        Route::get('/create', [RequestDonasiController::class, 'create'])->name('organisasi.request-donasi.create');
        Route::post('/', [RequestDonasiController::class, 'store'])->name('organisasi.request-donasi.store');
        Route::get('/{id_request}/edit', [RequestDonasiController::class, 'edit'])->name('organisasi.request-donasi.edit');
        Route::put('/{id_request}', [RequestDonasiController::class, 'update'])->name('organisasi.request-donasi.update');
        Route::delete('/{id_request}', [RequestDonasiController::class, 'destroy'])->name('organisasi.request-donasi.destroy');
    });
});

Route::middleware(['auth:pegawai'])->group(function () {
    Route::get('/owner/dashboard', function () {
        return view('dashboards.owner');
    })->name('owner.dashboard');

    Route::get('/admin/dashboard', function () {
        return view('dashboards.admin');
    })->name('admin.dashboard');

    Route::get('/cs/dashboard', function () {
        return view('dashboards.cs');
    })->name('cs.dashboard');

    Route::get('/gudang/dashboard', function () {
        return view('dashboards.gudang');
    })->name('gudang');

    Route::get('/kurir', function () {
        return view('dashboards.kurir');
    })->name('kurir');

    Route::get('/hunter/dashboard', function () {
        return view('dashboards.hunter');
    })->name('hunter.dashboard');

    Route::prefix('cs/penitip')->group(function () {
        Route::get('/', [PenitipController::class, 'index'])->name('cs.penitip.index');
        Route::get('/create', [PenitipController::class, 'create'])->name('cs.penitip.create');
        Route::post('/', [PenitipController::class, 'store'])->name('cs.penitip.store');
        Route::get('/{id_penitip}/edit', [PenitipController::class, 'edit'])->name('cs.penitip.edit');
        Route::put('/{id_penitip}', [PenitipController::class, 'update'])->name('cs.penitip.update');
        Route::patch('/{id_penitip}/deactivate', [PenitipController::class, 'deactivate'])->name('cs.penitip.deactivate');
        Route::patch('/{id_penitip}/activate', [PenitipController::class, 'activate'])->name('cs.penitip.activate');
    });



    Route::prefix('gudang/barang-titipan')->group(function () {
        Route::get('/', [PegawaiController::class, 'barangTitipanIndex'])->name('gudang.barang-titipan.index');
        Route::post('/{kode_barang}/record-pickup', [PegawaiController::class, 'recordPickup'])->name('gudang.barang-titipan.record-pickup');
    });
});