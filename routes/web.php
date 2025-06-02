<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\RequestDonasiController;

Route::get('/', function () {
    return view('homepage');
})->name('homepage');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Register
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register/user', [PembeliController::class, 'registerUser'])->name('registerUser');
Route::post('/register/organisasi', [OrganisasiController::class, 'registerORG'])->name('registerORG');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Profile Page
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

Route::middleware(['auth:pembeli'])->group(function () {
    Route::get('/pembeli/dashboard', [PembeliController::class, 'dashboard'])->name('pembeli.dashboard');
    Route::get('/pembeli/transaksi/{id_pembelian}', [PembeliController::class, 'transactionDetail'])->name('pembeli.transaction.detail');
});

Route::middleware(['auth:penitip'])->group(function () {
    Route::get('/penitip/dashboard', function () {
        return view('dashboards.penitip');
    })->name('penitip.dashboard');
});

Route::middleware(['auth:organisasi'])->group(function () {
    Route::get('/organisasi/dashboard', function () {
        return view('dashboards.organisasi');
    })->name('organisasi.dashboard');

    Route::middleware('auth:organisasi')->group(function () {
        Route::get('/organisasi/request-donasi/create', [RequestDonasiController::class, 'create'])->name('organisasi.request-donasi.create');
        Route::post('/organisasi/request-donasi', [RequestDonasiController::class, 'store'])->name('organisasi.request-donasi.store');
        Route::get('/organisasi/request-donasi/', [RequestDonasiController::class, 'index'])->name('organisasi.request-donasi.index');
        Route::post('/organisasi/request-donasi/', [RequestDonasiController::class, 'store'])->name('organisasi.request-donasi.store');
        Route::get('/organisasi/request-donasi/{id_request}/edit', [RequestDonasiController::class, 'edit'])->name('organisasi.request-donasi.edit');
        Route::put('/organisasi/request-donasi/{id_request}', [RequestDonasiController::class, 'update'])->name('organisasi.request-donasi.update');
        Route::delete('/organisasi/request-donasi/{id_request}', [RequestDonasiController::class, 'destroy'])->name('organisasi.request-donasi.destroy');
    });

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
    })->name('gudang.dashboard');

    Route::get('/kurir/dashboard', function () {
        return view('dashboards.kurir');
    })->name('kurir.dashboard');

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
});