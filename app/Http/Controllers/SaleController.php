<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Partner;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Show the form for creating a new sale.
     */
    public function create()
    {
        $partners = Partner::orderBy('name')->get();
        $categories = Category::query()
            ->orderBy('group_name')
            ->orderBy('name')
            ->get();
        $groupedCategories = $categories->groupBy(fn ($category) => $category->display_group_name ?: 'Lainnya');

        return view('sales.create', compact(
            'partners',
            'categories',
            'groupedCategories'
        ));
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'partner_id' => 'required|exists:partners,id',
            'category_id' => 'required|exists:categories,id',
            'price_type' => 'required|in:eceran,grosir',
            'quantity_sold_kg' => 'required|numeric|min:0.01',
        ]);

        $category = Category::findOrFail($validated['category_id']);
        $quantitySold = $validated['quantity_sold_kg'];
        $pricePerKg = $category->getPriceForType($validated['price_type']);

        // Logic: Check Available Stock using model attribute
        $availableStock = $category->current_stock;

        if ($quantitySold > $availableStock) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['quantity_sold_kg' => "Stok tidak cukup! Stok tersedia untuk {$category->name} adalah " . number_format($availableStock, 2) . " kg."]);
        }

        // Logic: Calculate Total Price
        $totalPrice = $quantitySold * $pricePerKg;

        // Start Transaction for data integrity
        DB::transaction(function () use ($validated, $totalPrice, $pricePerKg) {
            Sale::create([
                'date' => $validated['date'],
                'partner_id' => $validated['partner_id'],
                'category_id' => $validated['category_id'],
                'price_type' => $validated['price_type'],
                'quantity_sold_kg' => $validated['quantity_sold_kg'],
                'price_per_kg' => $pricePerKg,
                'total_price' => $totalPrice,
            ]);
        });

        $partnerName = Partner::find($validated['partner_id'])->name;
        return redirect()->route('sales.create')
            ->with('success', "Penjualan untuk {$partnerName} berhasil dicatat. Total belanja: Rp " . number_format($totalPrice, 0, ',', '.'));
    }
}
