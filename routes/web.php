<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| All routes are protected by the 'auth' middleware (Laravel Breeze).
| Only authenticated users (cashiers/admins) can access the POS system.
|
*/

Route::get('/', function () {
    /** @var \App\Models\User|null $user */
    $user = auth()->user();

    if ($user && $user->isCashier()) {
        return redirect()->route('pos.index');
    }

    return redirect()->route('dashboard');
});

// ── Authenticated Routes ──────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // ── Admin Only Routes ────────────────────────────────────────────────
    Route::middleware([\App\Http\Middleware\IsAdmin::class])->group(function () {
        // Dashboard (Analytics)
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Product Management (CRUD)
        Route::resource('products', ProductController::class)
            ->except(['show']); // We don't need a show page for products

        // Stock Adjustment
        Route::get('/stocks/adjustment', [StockController::class, 'adjustment'])
            ->name('stocks.adjustment');
        Route::post('/stocks/adjustment', [StockController::class, 'storeAdjustment'])
            ->name('stocks.storeAdjustment');

        // Category Management (CRUD)
        Route::resource('categories', CategoryController::class)
            ->except(['show']);
    });

    // ── Cashier & Admin Shared Routes ────────────────────────────────────
    // POS Cashier Interface
    Route::get('/pos', [POSController::class, 'index'])
        ->name('pos.index');
    Route::get('/api/pos/products', [POSController::class, 'getProducts'])
        ->name('pos.products');

    // API-like Route for Barcode Scanner (REMOVED)
    // Route::get('/products/barcode/{barcode}', [ProductController::class, 'findByBarcode']);

    // Transactions
    Route::post('/transactions/checkout', [TransactionController::class, 'checkout'])
        ->name('transactions.checkout');

    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');

    Route::get('/transactions/export', [TransactionController::class, 'exportCsv'])
        ->name('transactions.export');

    Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])
        ->name('transactions.receipt');
});

// Include Breeze auth routes (login, register, etc.)
require __DIR__.'/auth.php';
