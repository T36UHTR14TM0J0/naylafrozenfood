<?php

use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\UserController;

// use App\Http\Controllers\Admin\AdminController;
// use App\Http\Controllers\Owner\OwnerController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Password Reset Routes
Route::get('forgot_password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot_password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset_password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset_password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    // Route untuk ProfileController yang kita buat sebelumnya
    Route::prefix('profile')->group(function () {
        Route::get('edit', [ProfileController::class, 'index'])->name('profile.edit');
        Route::put('update', [ProfileController::class, 'update'])->name('profile.update');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('kategori', KategoriController::class);
    Route::resource('satuan', SatuanController::class);
    Route::resource('item', ItemController::class);
});

// // Owner Routes
Route::prefix('owner')->middleware(['auth', 'owner'])->group(function () {
    Route::get('user', [UserController::class, 'index'])->name('user.index');
    Route::get('user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    Route::resource('supplier', SupplierController::class);
});
