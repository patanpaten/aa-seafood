<?php

use App\Http\Controllers\IncomingStockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StockAdjustmentController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Accessible by both Owner and Admin Gudang
    Route::prefix('incoming-stocks')->name('incoming-stocks.')->group(function () {
        Route::get('/create', [IncomingStockController::class, 'create'])->name('create');
        Route::post('/', [IncomingStockController::class, 'store'])->name('store');
    });

    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/create', [SaleController::class, 'create'])->name('create');
        Route::post('/', [SaleController::class, 'store'])->name('store');
    });

    // Restricted to OWNER ONLY
    Route::middleware(['role:owner'])->group(function () {
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
        });

        // Master Data CRUDs
        Route::get('suppliers', function () {
            return redirect()->route('incoming-stocks.create');
        })->name('suppliers.index');
        Route::get('partners', function () {
            return redirect()->route('sales.create');
        })->name('partners.index');

        Route::resource('suppliers', SupplierController::class)->except(['show', 'index']);
        Route::resource('partners', PartnerController::class)->except(['show', 'index']);
        Route::resource('categories', CategoryController::class)->except(['show']);

        // Stock Adjustment (Stock Opname)
        Route::resource('stock-adjustments', StockAdjustmentController::class)->only(['index', 'create', 'store']);
    });

});
