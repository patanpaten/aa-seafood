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
            'partner_id' => 'nullable|exists:partners,id',
            'buyer_name' => 'nullable|string|max:255|required_without:partner_id',
            'category_id' => 'required|exists:categories,id',
            'price_type' => 'nullable|in:eceran,grosir',
            'quantity_sold_kg' => 'required|numeric|min:0.01',
            'price_per_kg' => 'required|numeric|min:0',
        ]);

        $category = Category::findOrFail($validated['category_id']);
        $partner = ! empty($validated['partner_id'])
            ? Partner::find($validated['partner_id'])
            : null;
        $buyerName = $partner?->name ?: trim((string) ($validated['buyer_name'] ?? ''));
        $quantitySold = (float) $validated['quantity_sold_kg'];
        $pricePerKg = (float) $validated['price_per_kg'];
        $retailPrice = (float) $category->retail_price;
        $wholesalePrice = (float) $category->wholesale_price;
        $resolvedPriceType = $validated['price_type'] ?? null;

        if (! $resolvedPriceType) {
            $resolvedPriceType = abs($pricePerKg - $wholesalePrice) < 0.0001 ? 'grosir' : 'eceran';
        }

        if (! $partner && $buyerName === '') {
            $message = 'Nama pembeli wajib diisi untuk penjualan eceran.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Data penjualan tidak bisa disimpan.',
                    'errors' => [
                        'buyer_name' => [$message],
                    ],
                ], 422);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['buyer_name' => $message]);
        }

        // Logic: Check Available Stock using model attribute
        $availableStock = $category->current_stock;

        if ($quantitySold > $availableStock) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Data penjualan tidak bisa disimpan.',
                    'errors' => [
                        'quantity_sold_kg' => ["Stok tidak cukup! Stok tersedia untuk {$category->name} adalah " . number_format($availableStock, 2) . " kg."],
                    ],
                ], 422);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['quantity_sold_kg' => "Stok tidak cukup! Stok tersedia untuk {$category->name} adalah " . number_format($availableStock, 2) . " kg."]);
        }

        // Logic: Calculate Total Price
        $totalPrice = $quantitySold * $pricePerKg;

        // Start Transaction for data integrity
        DB::transaction(function () use ($validated, $totalPrice, $pricePerKg, $buyerName, $resolvedPriceType) {
            Sale::create([
                'date' => $validated['date'],
                'partner_id' => $validated['partner_id'] ?? null,
                'buyer_name' => $buyerName ?: 'Pembeli Umum',
                'category_id' => $validated['category_id'],
                'price_type' => $resolvedPriceType,
                'quantity_sold_kg' => $validated['quantity_sold_kg'],
                'price_per_kg' => $pricePerKg,
                'total_price' => $totalPrice,
            ]);
        });

        $buyerLabel = $buyerName ?: 'Pembeli Umum';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => "Penjualan untuk {$buyerLabel} berhasil dicatat.",
                'total' => number_format($totalPrice, 0, ',', '.'),
            ], 201);
        }

        return redirect()->route('sales.create')
            ->with('success', "Penjualan untuk {$buyerLabel} berhasil dicatat. Total belanja: Rp " . number_format($totalPrice, 0, ',', '.'));
    }
}
