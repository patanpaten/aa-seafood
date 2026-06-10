<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\IncomingStock;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    /**
     * Display a listing of the stock adjustments.
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $selectedSupplierId = $request->input('supplier_id');
        $selectedCategoryId = $request->input('category_id');

        // 1. Ambil semua kategori
        $categoriesQuery = Category::query();
        if ($selectedCategoryId) {
            $categoriesQuery->where('id', $selectedCategoryId);
        }
        $categoriesList = Category::orderBy('name')->get();
        $categories = $categoriesQuery->get()->keyBy('id');

        // 2. Hitung Saldo Awal (Stok sebelum startDate) untuk tiap kategori
        $initialBalances = [];
        foreach ($categories as $id => $cat) {
            $incomingBefore = \App\Models\IncomingStock::where('category_id', $id);
            $salesBefore = \App\Models\Sale::where('category_id', $id);
            $adjBefore = \App\Models\StockAdjustment::where('category_id', $id);

            if ($startDate) {
                $incomingBefore->where('created_at', '<', $startDate);
                $salesBefore->where('created_at', '<', $startDate);
                $adjBefore->where('created_at', '<', $startDate);
            } else {
                // Jika tidak ada filter tanggal, saldo awal adalah 0
                $initialBalances[$id] = 0;
                continue;
            }

            $initialBalances[$id] = $incomingBefore->sum('actual_weight') 
                                  - $salesBefore->sum('quantity_sold_kg') 
                                  + $adjBefore->sum('difference');
        }

        // 3. Ambil detail transaksi stok masuk yang TERFILTER (Urutkan dari yang LAMA ke BARU untuk perhitungan rolling)
        $incomingStocksQuery = IncomingStock::with(['category', 'supplier']);
        if ($startDate) $incomingStocksQuery->whereDate('created_at', '>=', $startDate);
        if ($endDate) $incomingStocksQuery->whereDate('created_at', '<=', $endDate);
        if ($selectedSupplierId) $incomingStocksQuery->where('supplier_id', $selectedSupplierId);
        if ($selectedCategoryId) $incomingStocksQuery->where('category_id', $selectedCategoryId);

        $incomingStocks = $incomingStocksQuery->oldest()->get()
            ->groupBy('category_id');

        // 4. Hitung Sisa Stok Global (Real-time) untuk header/modal
        $categoriesWithSum = Category::withSum('incomingStocks', 'actual_weight')
            ->withSum('sales', 'quantity_sold_kg')
            ->withSum('adjustments', 'difference')
            ->get()
            ->keyBy('id');

        $suppliers = \App\Models\Supplier::orderBy('name')->get();
        $adjustments = StockAdjustment::with(['category', 'user'])
            ->latest()
            ->paginate(10);

        return view('stock_adjustments.index', compact(
            'incomingStocks', 
            'categories', 
            'categoriesWithSum',
            'initialBalances',
            'adjustments',
            'suppliers',
            'categoriesList'
        ));
    }

    /**
     * Show the form for creating a new stock adjustment.
     */
    public function create()
    {
        $categories = Category::query()
            ->orderBy('group_name')
            ->orderBy('name')
            ->get();
        $groupedCategories = $categories->groupBy(fn ($category) => $category->display_group_name ?: 'Lainnya');
        $selectedCategoryId = request()->integer('category_id');

        return view('stock_adjustments.create', compact(
            'categories',
            'groupedCategories',
            'selectedCategoryId'
        ));
    }

    /**
     * Store a newly created stock adjustment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'actual_stock' => 'required|numeric|min:0',
            'reason' => 'required|string|max:1000',
        ]);

        $category = Category::findOrFail($validated['category_id']);
        $previousStock = $category->current_stock;
        $actualStock = $validated['actual_stock'];
        $difference = $actualStock - $previousStock;

        DB::transaction(function () use ($validated, $previousStock, $actualStock, $difference) {
            StockAdjustment::create([
                'category_id' => $validated['category_id'],
                'previous_stock' => $previousStock,
                'actual_stock' => $actualStock,
                'difference' => $difference,
                'reason' => $validated['reason'],
                'adjusted_by' => Auth::id(),
            ]);
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => "Hasil cek stok untuk {$category->name} berhasil disimpan.",
                'difference' => number_format($difference, 2),
            ], 201);
        }

        return redirect()->route('stock-adjustments.index')
            ->with('success', "Hasil cek stok untuk {$category->name} berhasil disimpan. Selisih: " . number_format($difference, 2) . " kg.");
    }
}
