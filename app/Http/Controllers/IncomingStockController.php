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
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();

        $categories = Category::query()
            ->orderBy('group_name')
            ->orderBy('name')
            ->get();

        $groupedCategories = $categories->groupBy(fn ($category) => $category->display_group_name ?: 'Lainnya');

        return view('incoming_stocks.create', compact(
            'suppliers',
            'categories',
            'groupedCategories'
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
            'purchase_price_per_kg' => 'required|numeric|min:0',
            'receipt_weight' => 'required|numeric|min:0.01',
            'actual_weight' => 'required|numeric|min:0.01',
        ]);

        // Hitung selisih berat
        $shrinkage = $validated['receipt_weight'] - $validated['actual_weight'];
        $totalPurchasePrice = $validated['actual_weight'] * $validated['purchase_price_per_kg'];
        
        // Cek jika selisih berat > 5% dari berat di nota
        $threshold = $validated['receipt_weight'] * 0.05;
        $status = ($shrinkage > $threshold) ? 'Warning/Loss' : 'Normal';

        IncomingStock::create([
            'date' => $validated['date'],
            'supplier_id' => $validated['supplier_id'],
            'category_id' => $validated['category_id'],
            'purchase_price_per_kg' => $validated['purchase_price_per_kg'],
            'total_purchase_price' => $totalPurchasePrice,
            'receipt_weight' => $validated['receipt_weight'],
            'actual_weight' => $validated['actual_weight'],
            'shrinkage_weight' => $shrinkage,
            'status' => $status,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Barang masuk berhasil dicatat.',
                'total_purchase_price' => number_format($totalPurchasePrice, 0, ',', '.'),
                'warning' => $status === 'Warning/Loss'
                    ? "Selisih berat barang ini mencapai " . number_format($shrinkage, 2) . " kg (" . number_format(($shrinkage / $validated['receipt_weight']) * 100, 1) . "%). Nilai ini melebihi batas aman 5%!"
                    : null,
            ], 201);
        }

        if ($status === 'Warning/Loss') {
            return redirect()->route('incoming-stocks.create')
                ->with('success', 'Barang masuk berhasil dicatat.')
                ->with('warning', "Selisih berat barang ini mencapai " . number_format($shrinkage, 2) . " kg (" . number_format(($shrinkage / $validated['receipt_weight']) * 100, 1) . "%). Nilai ini melebihi batas aman 5%!");
        }

        return redirect()->route('incoming-stocks.create')->with('success', "Barang masuk berhasil dicatat.");
    }
}
