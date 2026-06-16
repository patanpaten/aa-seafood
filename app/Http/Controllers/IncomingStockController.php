<?php

namespace App\Http\Controllers;

use App\Models\IncomingStock;
use App\Models\Supplier;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'items' => 'required|array|min:1',
            'items.*.category_id' => 'required|exists:categories,id',
            'items.*.purchase_price_per_kg' => 'required|numeric|min:0',
            'items.*.receipt_weight' => 'required|numeric|min:0.01',
            'items.*.actual_weight' => 'required|numeric|min:0.01',
        ]);

        $totalPurchasePrice = 0;
        $hasWarning = false;
        $warnings = [];

        DB::transaction(function () use ($validated, &$totalPurchasePrice, &$hasWarning, &$warnings) {
            foreach ($validated['items'] as $item) {
                // Hitung selisih berat
                $shrinkage = $item['receipt_weight'] - $item['actual_weight'];
                $itemTotal = $item['actual_weight'] * $item['purchase_price_per_kg'];
                $totalPurchasePrice += $itemTotal;
                
                // Cek jika selisih berat > 5% dari berat di nota
                $threshold = $item['receipt_weight'] * 0.05;
                $status = ($shrinkage > $threshold) ? 'Warning/Loss' : 'Normal';

                if ($status === 'Warning/Loss') {
                    $hasWarning = true;
                    $categoryName = Category::find($item['category_id'])?->name ?? 'Barang';
                    $warnings[] = "{$categoryName}: Selisih berat " . number_format($shrinkage, 2) . " kg (" . number_format(($shrinkage / $item['receipt_weight']) * 100, 1) . "%).";
                }

                IncomingStock::create([
                    'date' => $validated['date'],
                    'supplier_id' => $validated['supplier_id'],
                    'category_id' => $item['category_id'],
                    'purchase_price_per_kg' => $item['purchase_price_per_kg'],
                    'total_purchase_price' => $itemTotal,
                    'receipt_weight' => $item['receipt_weight'],
                    'actual_weight' => $item['actual_weight'],
                    'shrinkage_weight' => $shrinkage,
                    'status' => $status,
                ]);
            }
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Barang masuk berhasil dicatat.',
                'total_purchase_price' => number_format($totalPurchasePrice, 0, ',', '.'),
                'warning' => $hasWarning ? implode(' ', $warnings) : null,
            ], 201);
        }

        if ($hasWarning) {
            return redirect()->route('incoming-stocks.create')
                ->with('success', 'Barang masuk berhasil dicatat.')
                ->with('warning', implode(' ', $warnings));
        }

        return redirect()->route('incoming-stocks.create')->with('success', "Barang masuk berhasil dicatat.");
    }

    public function destroy(IncomingStock $incomingStock)
    {
        $incomingStock->delete();
        return redirect()->back()->with('success', "Transaksi stok masuk berhasil dihapus.");
    }
}
