<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    /**
     * Display a listing of the stock adjustments.
     */
    public function index()
    {
        $categories = Category::query()
            ->withSum('incomingStocks', 'actual_weight')
            ->withSum('sales', 'quantity_sold_kg')
            ->withSum('adjustments', 'difference')
            ->orderBy('group_name')
            ->orderBy('name')
            ->get();
        $adjustments = StockAdjustment::with(['category', 'user'])
            ->latest()
            ->paginate(10);

        return view('stock_adjustments.index', compact('adjustments', 'categories'));
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
