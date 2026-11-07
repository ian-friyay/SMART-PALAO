<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TelemetryController;
use App\Http\Controllers\VitalityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Smart Palao API Routes
|--------------------------------------------------------------------------
| Custom REST API for IoT duck egg incubation and e-commerce platform.
*/

// ==========================================
// 1. ESP32 Microcontroller Ingestion (Lightweight & High Performance)
// ==========================================
// Microcontroller pushes SHT31 ambient temp & humidity + status data
Route::post('/telemetry/push', [TelemetryController::class, 'push'])->name('api.telemetry.push');

// Real-time telemetry query endpoints (supports initial load and polling fallback)
Route::get('/telemetry/latest/{chamberId?}', [TelemetryController::class, 'latest'])->name('api.telemetry.latest');
Route::get('/telemetry/history/{chamberId?}', [TelemetryController::class, 'history'])->name('api.telemetry.history');
Route::post('/telemetry/simulate', [TelemetryController::class, 'simulate'])->name('api.telemetry.simulate');

// ==========================================
// 2. Authentication (Sanctum Tokens)
// ==========================================
Route::post('/auth/login', [AuthController::class, 'apiLogin'])->name('api.auth.login');
Route::post('/auth/register', [AuthController::class, 'apiRegister'])->name('api.auth.register');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'apiMe'])->name('api.auth.me');
    Route::post('/auth/logout', [AuthController::class, 'apiLogout'])->name('api.auth.logout');
});

// ==========================================
// 3. E-Commerce Storefront (Buyer & Public)
// ==========================================
Route::get('/store/products', [StoreController::class, 'getProducts'])->name('api.store.products');
Route::post('/store/checkout', [StoreController::class, 'checkout'])->name('api.store.checkout');

// ==========================================
// 4. Incubation Control & Admin API (Owner Role Only)
// ==========================================
// Note: We allow session auth or sanctum auth for dashboard ajax requests
Route::middleware(['auth:sanctum,web', 'role:owner'])->group(function () {
    // Hardware PID & Chamber controls
    Route::post('/telemetry/controls/{chamberId?}', [TelemetryController::class, 'updateControls'])->name('api.telemetry.controls');

    // Vitality Candling Logs
    Route::get('/vitality/logs', [VitalityController::class, 'index'])->name('api.vitality.index');
    Route::post('/vitality/logs', [VitalityController::class, 'store'])->name('api.vitality.store');
    Route::delete('/vitality/logs/{id}', [VitalityController::class, 'destroy'])->name('api.vitality.destroy');

    // E-Commerce Admin & Inventory
    Route::post('/inventory/update', [AdminController::class, 'updateInventory'])->name('api.inventory.update');
    Route::post('/inventory/bulk-update', [AdminController::class, 'bulkUpdateInventory'])->name('api.inventory.bulkUpdate');
    Route::post('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('api.orders.updateStatus');
});
