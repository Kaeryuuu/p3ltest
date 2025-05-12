<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\OrganisasiController;


Route::get('/', function () {
    $categories = [
        (object) ['name' => 'Fashion', 'image_url' => 'images/fashion.jpg'],
        (object) ['name' => 'Elektronik', 'image_url' => 'images/electronics.jpg'],
    ];

    $products = [
        (object) ['id' => 1, 'name' => 'Produk 1', 'price' => 100000, 'description' => 'Deskripsi untuk produk 1', 'image_url' => 'images/product1.jpg'],
        (object) ['id' => 2, 'name' => 'Produk 2', 'price' => 150000, 'description' => 'Deskripsi untuk produk 2', 'image_url' => 'images/product2.jpg'],
    ];

    return view('homepage', compact('categories', 'products'));
});

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

//Register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [PembeliController::class, 'store'])->name('registerUser');
Route::post('/register/organization', [OrganisasiController::class, 'store'])->name('registerORG');


//Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Profile Page
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
