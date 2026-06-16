<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\IncomingStockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// GUEST ROUTES (Belum Login)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ==========================================
// PUBLIC / COURIER OPEN ROUTES (Tanpa Auth)
// ==========================================
// Akses langsung input bukti oleh kurir melalui link WhatsApp
Route::prefix('delivery')->name('delivery.')->group(function () {
    Route::get('/{sale}/input', [SaleController::class, 'deliveryInput'])->name('input');
    Route::post('/{sale}/update', [SaleController::class, 'deliveryUpdate'])->name('update');
});


// ==========================================
// PROTECTED ROUTES (Harus Login)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // Global App Routes
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Courier Internal Confirmation List
    Route::prefix('konfirmasi')->name('delivery.')->group(function () {
        Route::get('/', [SaleController::class, 'deliveryList'])->name('index'); 
    });
    
    // Shared Access: Owner & Admin Gudang
    // 1. Incoming Stocks
    Route::prefix('incoming-stocks')->name('incoming-stocks.')->group(function () {
        Route::get('/create', [IncomingStockController::class, 'create'])->name('create');
        Route::post('/', [IncomingStockController::class, 'store'])->name('store');
        Route::delete('/{incomingStock}', [IncomingStockController::class, 'destroy'])->name('destroy');
    });

    // 2. Sales / Penjualan
        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('/create', [SaleController::class, 'create'])->name('create');
            Route::post('/', [SaleController::class, 'store'])->name('store');
            Route::patch('/{sale}/update-status', [SaleController::class, 'updateStatus'])->name('update-status');
            Route::patch('/{sale}', [SaleController::class, 'update'])->name('update');
            Route::delete('/{sale}', [SaleController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-delivery', [SaleController::class, 'bulkDelivery'])->name('bulk-delivery');
            Route::post('/bulk-update-status', [SaleController::class, 'bulkDelivery'])->name('bulk-update-status');
        });


    // ==========================================
    // RESTRICTED ROUTES: OWNER ONLY
    // ==========================================
    Route::middleware(['role:owner'])->group(function () {
        
        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
        });

        // Master Data CRUDs
        Route::resource('suppliers', SupplierController::class)->except(['show', 'index']);
        Route::resource('categories', CategoryController::class)->except(['show']);
        
        // Partners / Pelanggan & AJAX Modals Data
        Route::prefix('partners')->name('partners.')->group(function () {
            Route::post('/ajax-store', [PartnerController::class, 'ajaxStore'])->name('ajax-store');
            Route::post('/{partner}/ajax-update', [PartnerController::class, 'ajaxUpdate'])->name('ajax-update');
        });
        Route::resource('partners', PartnerController::class)->except(['show', 'index']);

        // Stock Adjustment (Stock Opname)
        Route::resource('stock-adjustments', StockAdjustmentController::class)->only(['index', 'create', 'store']);
    });

});