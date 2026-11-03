<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Smart Palao
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. Module 2: E-Commerce Storefront (Buyer & Public)
// ==========================================
Route::get('/', [StoreController::class, 'index'])->name('store');
Route::get('/about', [StoreController::class, 'about'])->name('store.about');
Route::get('/cart', [StoreController::class, 'cart'])->name('store.cart');
Route::post('/branch/set', [StoreController::class, 'setBranch'])->name('store.setBranch');
Route::post('/checkout', [StoreController::class, 'checkout'])->name('store.checkout');
Route::get('/order-confirmation/{orderNumber}', [StoreController::class, 'confirmation'])->name('store.confirmation');

// Buyer-only protected route
Route::middleware(['auth'])->group(function () {
    Route::get('/my-orders', [StoreController::class, 'buyerOrders'])->name('store.orders');
});

// ==========================================
// 2. Authentication & Role Switcher
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 1-Click Role Switcher for instant evaluator demo
Route::get('/auth/quick-switch/{role}', [AuthController::class, 'quickLogin'])->name('auth.quickLogin');

// ==========================================
// 3. Module 1 & 3: Owner / Admin Portal (Strict RBAC: Owner Only)
// ==========================================
Route::prefix('admin')->middleware(['auth', 'role:owner'])->group(function () {
    // Module 1: IoT Technical Incubation Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Module 3: Inventory Management
    Route::get('/inventory', [AdminController::class, 'inventory'])->name('admin.inventory');
    Route::post('/inventory/update', [AdminController::class, 'updateInventory'])->name('admin.inventory.update');
    Route::post('/inventory/bulk-update', [AdminController::class, 'bulkUpdateInventory'])->name('admin.inventory.bulkUpdate');

    // Module 3: Order Fulfillment Kanban Board
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::post('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.updateStatus');
});
