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
        $adjustments = StockAdjustment::with(['category', 'user'])->latest()->paginate(10);
        return view('stock_adjustments.index', compact('adjustments'));
    }

    /**
     * Show the form for creating a new stock adjustment.
     */
    public function create()
    {
        $categories = Category::all();
        return view('stock_adjustments.create', compact('categories'));
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

        return redirect()->route('stock-adjustments.index')
            ->with('success', "Stok Opname untuk {$category->name} berhasil disimpan. Selisih: " . number_format($difference, 2) . " kg.");
    }
}
