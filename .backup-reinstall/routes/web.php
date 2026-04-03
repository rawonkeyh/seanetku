<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\VoucherController as AdminVoucherController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;

// Customer Routes
Route::get('/', function () {
    return view('customer.index-light');
});

Route::get('/success', function () {
    return view('customer.success-light');
});

// Admin Auth Routes (No Auth Required)
Route::prefix('admin')->name('admin.')->middleware('web')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin Protected Routes (Auth Required)
Route::prefix('admin')->name('admin.')->middleware(['web', 'admin.auth'])->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('admin.dashboard-light');
    })->name('dashboard');

    // Package Management
    Route::resource('packages', AdminPackageController::class);

    // Voucher Management
    Route::resource('vouchers', AdminVoucherController::class);
    Route::get('vouchers/bulk/create', [AdminVoucherController::class, 'bulkCreate'])->name('vouchers.bulk.create');
    Route::post('vouchers/bulk/store', [AdminVoucherController::class, 'bulkStore'])->name('vouchers.bulk.store');

    // Transaction Management
    Route::resource('transactions', AdminTransactionController::class)->only(['index', 'show']);
});
