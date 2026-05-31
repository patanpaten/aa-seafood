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
    public function index(Request $request)
    {
        $selectedGroup = $request->query('group');
        $categoryGroups = Category::query()
            ->select('group_name')
            ->whereNotNull('group_name')
            ->distinct()
            ->orderBy('group_name')
            ->pluck('group_name');

        $adjustments = StockAdjustment::with(['category', 'user'])
            ->when($selectedGroup, function ($query) use ($selectedGroup) {
                $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('group_name', $selectedGroup));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('stock_adjustments.index', compact('adjustments', 'categoryGroups', 'selectedGroup'));
    }

    /**
     * Show the form for creating a new stock adjustment.
     */
    public function create(Request $request)
    {
        $selectedGroup = $request->query('group');
        $categoryGroups = Category::query()
            ->select('group_name')
            ->whereNotNull('group_name')
            ->distinct()
            ->orderBy('group_name')
            ->pluck('group_name');

        $categories = Category::query()
            ->when($selectedGroup, fn ($query) => $query->where('group_name', $selectedGroup))
            ->orderBy('group_name')
            ->orderBy('name')
            ->get();
        $groupedCategories = $categories->groupBy(fn ($category) => $category->group_name ?: 'Lainnya');

        return view('stock_adjustments.create', compact(
            'categories',
            'groupedCategories',
            'categoryGroups',
            'selectedGroup'
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

        return redirect()->route('stock-adjustments.index')
            ->with('success', "Stok Opname untuk {$category->name} berhasil disimpan. Selisih: " . number_format($difference, 2) . " kg.");
    }
}
