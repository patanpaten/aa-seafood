<?php

namespace App\Http\Controllers;

use App\Models\IncomingStock;
use App\Models\Supplier;
use App\Models\Category;
use Illuminate\Http\Request;

class IncomingStockController extends Controller
{
    /**
     * Show the form for creating a new incoming stock.
     */
    public function create(Request $request)
    {
        $suppliers = Supplier::orderBy('name')->get();
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

        return view('incoming_stocks.create', compact(
            'suppliers',
            'categories',
            'groupedCategories',
            'categoryGroups',
            'selectedGroup'
        ));
    }

    /**
     * Store a newly created incoming stock in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'category_id' => 'required|exists:categories,id',
            'receipt_weight' => 'required|numeric|min:0.01',
            'actual_weight' => 'required|numeric|min:0.01',
        ]);

        // Hitung penyusutan (Drip Loss)
        $shrinkage = $validated['receipt_weight'] - $validated['actual_weight'];
        
        // Cek jika penyusutan > 5% dari berat nota
        $threshold = $validated['receipt_weight'] * 0.05;
        $status = ($shrinkage > $threshold) ? 'Warning/Loss' : 'Normal';

        IncomingStock::create([
            'date' => $validated['date'],
            'supplier_id' => $validated['supplier_id'],
            'category_id' => $validated['category_id'],
            'receipt_weight' => $validated['receipt_weight'],
            'actual_weight' => $validated['actual_weight'],
            'shrinkage_weight' => $shrinkage,
            'status' => $status,
        ]);

        if ($status === 'Warning/Loss') {
            return redirect()->route('incoming-stocks.create')
                ->with('success', 'Stok masuk berhasil dicatat.')
                ->with('warning', "Penyusutan seafood ini mencapai " . number_format($shrinkage, 2) . " kg (" . number_format(($shrinkage / $validated['receipt_weight']) * 100, 1) . "%). Nilai ini melebihi batas toleransi 5%!");
        }

        return redirect()->route('incoming-stocks.create')->with('success', "Stok masuk berhasil dicatat.");
    }
}
