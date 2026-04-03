<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\PaymentCallbackController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    
    // Public routes
    Route::get('/packages', [PackageController::class, 'index']);
    Route::get('/packages/{id}', [PackageController::class, 'show']);
    
    // Transaction routes
    Route::post('/transactions', [TransactionController::class, 'create'])
        ->middleware('throttle:20,1');
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    Route::get('/transactions/{id}/status', [TransactionController::class, 'status']);
    Route::get('/transactions/order/{orderId}/status', [TransactionController::class, 'statusByOrderId']);
    
    // Payment callback (from Midtrans)
    Route::post('/payment/callback', [PaymentCallbackController::class, 'handle'])
        ->middleware('throttle:60,1');
    
    // Health check
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toIso8601String()
        ]);
    });
    
});
