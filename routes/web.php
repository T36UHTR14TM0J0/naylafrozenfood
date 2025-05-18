<?php

use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemStockController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;

// Auth Routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Register Routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Password Reset Routes
Route::get('forgot_password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot_password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset_password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset_password', [AuthController::class, 'resetPassword'])->name('password.update');

// Routes yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('edit', [ProfileController::class, 'index'])->name('profile.edit');
        Route::put('update', [ProfileController::class, 'update'])->name('profile.update');
    });

    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Kategori Routes
    Route::resource('kategori', KategoriController::class);
    
    // Satuan Routes
    Route::resource('satuan', SatuanController::class);

    // Item Routes
    Route::resource('item', ItemController::class);
    
    // Item Stock Routes
    Route::resource('stok', ItemStockController::class);
    
    // Transaksi Routes
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/store', [TransaksiController::class, 'store'])->name('transaksi.store');
    
    // QRIS Transaction Routes
    Route::post('/create-qris-transaction', [TransaksiController::class, 'createTransaction'])->name('create.qris.transaction');
    
    // Callback Routes
    Route::post('/transaksi/callback', [TransaksiController::class, 'handleCallback'])
    ->name('transaksi.callback')
    ->withoutMiddleware(['csrf']); // Nonaktifkan CSRF untuk eksternal API
});

// Owner Routes
Route::prefix('owner')->middleware(['auth', 'owner'])->group(function () {
    // User Management Routes
    Route::get('user', [UserController::class, 'index'])->name('user.index');
    Route::get('user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    // Supplier Routes
    Route::resource('supplier', SupplierController::class);
});
