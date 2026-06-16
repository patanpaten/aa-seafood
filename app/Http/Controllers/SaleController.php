<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Partner;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SaleController extends Controller
{
    public function create()
    {
        $partners = Partner::orderBy('name')->get();
        $categories = Category::query()
            ->orderBy('group_name')
            ->orderBy('name')
            ->get();
            
        $groupedCategories = $categories->groupBy(fn ($category) => $category->display_group_name ?: 'Lainnya');

        return view('penjualan', compact(
            'partners',
            'categories',
            'groupedCategories'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'partner_id' => 'nullable|exists:partners,id',
            'buyer_name' => 'nullable|string|max:255|required_without:partner_id',
            'items' => 'required|array|min:1',
            'items.*.category_id' => 'required|exists:categories,id',
            'items.*.price_type' => 'nullable|in:eceran,grosir',
            'items.*.quantity_sold_kg' => 'required|numeric|min:0.01',
            'items.*.price_per_kg' => 'required|numeric|min:0',
            'driver_name' => 'nullable|string|max:255',   
            'driver_phone' => 'nullable|string|max:20',    
        ]);

        $partner = ! empty($validated['partner_id']) ? Partner::find($validated['partner_id']) : null;
        $buyerName = $partner?->name ?: trim((string) ($validated['buyer_name'] ?? ''));

        if (! $partner && $buyerName === '') {
            $message = 'Nama pembeli wajib diisi untuk penjualan eceran.';
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Data penjualan tidak bisa disimpan.',
                    'errors' => ['buyer_name' => [$message]],
                ], 422);
            }
            return redirect()->back()->withInput()->withErrors(['buyer_name' => $message]);
        }

        // Validate stock for each item
        foreach ($validated['items'] as $item) {
            $category = Category::findOrFail($item['category_id']);
            $availableStock = $category->current_stock;
            $quantitySold = (float) $item['quantity_sold_kg'];
            
            if ($quantitySold > $availableStock) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Data penjualan tidak bisa disimpan.',
                        'errors' => [
                            'items' => ["Stok tidak cukup! Stok tersedia untuk {$category->name} adalah " . number_format($availableStock, 2) . " kg."],
                        ],
                    ], 422);
                }
                return redirect()->back()->withInput()->withErrors(['items' => "Stok tidak cukup! Stok tersedia untuk {$category->name} adalah " . number_format($availableStock, 2) . " kg."]);
            }
        }

        $totalPrice = 0;
        DB::transaction(function () use ($validated, $buyerName, &$totalPrice) {
            foreach ($validated['items'] as $item) {
                $category = Category::findOrFail($item['category_id']);
                $wholesalePrice = (float) $category->wholesale_price;
                $resolvedPriceType = $item['price_type'] ?? null;

                if (! $resolvedPriceType) {
                    $resolvedPriceType = abs((float)$item['price_per_kg'] - $wholesalePrice) < 0.0001 ? 'grosir' : 'eceran';
                }

                $quantitySold = (float) $item['quantity_sold_kg'];
                $pricePerKg = (float) $item['price_per_kg'];
                $itemTotalPrice = $quantitySold * $pricePerKg;
                $totalPrice += $itemTotalPrice;

                Sale::create([
                    'date' => $validated['date'],
                    'partner_id' => $validated['partner_id'] ?? null,
                    'buyer_name' => $buyerName ?: 'Pembeli Umum',
                    'category_id' => $item['category_id'],
                    'price_type' => $resolvedPriceType,
                    'quantity_sold_kg' => $item['quantity_sold_kg'],
                    'price_per_kg' => $pricePerKg,
                    'total_price' => $itemTotalPrice,
                    'status' => 'sedang diproses',
                    'driver_name' => $validated['driver_name'] ?? null,   
                    'driver_phone' => $validated['driver_phone'] ?? null, 
                ]);
            }
        });

        $buyerLabel = $buyerName ?: 'Pembeli Umum';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => "Penjualan untuk {$buyerLabel} berhasil dicatat.",
                'total' => number_format($totalPrice, 0, ',', '.'),
            ], 201);
        }

        return redirect()->back()->with('success', "Penjualan untuk {$buyerLabel} berhasil dicatat.");
    }

    /**
     * FUNGSI LAMA ANDA: TETAP UTUH & TIDAK BERUBAH
     * (Hanya untuk update status lewat tombol Kirim / Selesai)
     */
    public function updateStatus(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'status' => 'required|in:dalam perjalanan,selesai',
        ]);

        $sale->update([
            'status' => $validated['status']
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui menjadi: ' . $validated['status']);
    }

    public function bulkDelivery(Request $request)
    {
        $validated = $request->validate([
            'sale_ids' => 'required|array',
            'sale_ids.*' => 'exists:sales,id',
        ]);

        $count = 0;
        foreach ($validated['sale_ids'] as $saleId) {
            $sale = Sale::find($saleId);
            if ($sale && $sale->status === 'sedang diproses') {
                $sale->update(['status' => 'dalam perjalanan']);
                $count++;
            }
        }

        return response()->json([
            'message' => "Berhasil menandai {$count} transaksi sebagai dalam perjalanan!"
        ]);
    }


public function update(Request $request, Sale $sale)
{
    if ($sale->status !== 'sedang diproses') {
        return $this->errorResponse($request, "Data penjualan tidak dapat diubah karena statusnya sudah {$sale->status}.");
    }

    $validated = $request->validate([
        'date' => 'required|date',
        'category_id' => 'required|exists:categories,id',
        'quantity_sold_kg' => 'required|numeric|min:0.01',
        'price_per_kg' => 'required|numeric|min:0',
        'driver_name' => 'nullable|string|max:255',
        'driver_phone' => 'nullable|string|max:20',
    ]);

    $newCategory = Category::findOrFail($validated['category_id']);
    $newQuantity = (float) $validated['quantity_sold_kg'];
    $pricePerKg = (float) $validated['price_per_kg'];
    
    $oldQuantity = (float) $sale->quantity_sold_kg;

    // VALIDASI STOK (Menggunakan property current_stock yang dinamis)
    if ($sale->category_id === $newCategory->id) {
        // Jika kategori barang SAMA, sisa stok aman dihitung dari stok saat ini ditambah kuantitas lama yang mau diganti
        $safeStock = $newCategory->current_stock + $oldQuantity;
        if ($newQuantity > $safeStock) {
            return $this->errorResponse($request, "Stok tidak cukup! Maksimal stok yang tersedia setelah penyesuaian adalah " . number_format($safeStock, 2) . " kg.");
        }
    } else {
        // Jika kategori barang BERUBAH, pastikan kategori baru punya stok yang cukup
        if ($newQuantity > $newCategory->current_stock) {
            return $this->errorResponse($request, "Stok tidak cukup! Stok tersedia untuk {$newCategory->name} adalah " . number_format($newCategory->current_stock, 2) . " kg.");
        }
    }

    $wholesalePrice = (float) $newCategory->wholesale_price;
    $resolvedPriceType = abs($pricePerKg - $wholesalePrice) < 0.0001 ? 'grosir' : 'eceran';
    $totalPrice = $newQuantity * $pricePerKg;

    // UPDATE DATA (Tanpa decrement/increment manual karena stok terhitung otomatis)
    DB::transaction(function () use ($sale, $validated, $resolvedPriceType, $totalPrice, $pricePerKg, $newQuantity) {
        $sale->update([
            'date' => $validated['date'],
            'category_id' => $validated['category_id'],
            'price_type' => $resolvedPriceType,
            'quantity_sold_kg' => $newQuantity,
            'price_per_kg' => $pricePerKg,
            'total_price' => $totalPrice,
            'driver_name' => $validated['driver_name'] ?? null,
            'driver_phone' => $validated['driver_phone'] ?? null,
        ]);
    });

    if ($request->expectsJson()) {
        return response()->json([
            'message' => "Data penjualan untuk {$sale->buyer_name} berhasil diperbarui.",
            'total' => number_format($totalPrice, 0, ',', '.'),
        ], 200);
    }

    return redirect()->back()->with('success', "Data penjualan untuk {$sale->buyer_name} berhasil diperbarui.");
}


public function destroy(Request $request, Sale $sale)
{
    if ($sale->status !== 'sedang diproses') {
        return redirect()->back()->with('error', "Data tidak bisa dihapus karena pesanan sudah {$sale->status}.");
    }

    DB::transaction(function () use ($sale) {
        // Hapus foto bukti pengiriman dari storage jika ada
        if ($sale->delivery_proof && Storage::disk('public')->exists('delivery_proofs/' . $sale->delivery_proof)) {
            Storage::disk('public')->delete('delivery_proofs/' . $sale->delivery_proof);
        }

        // Cukup hapus data penjualan, stok otomatis kembali normal lewat rumus di Model Category!
        $sale->delete();
    });

    return redirect()->back()->with('success', "Data penjualan untuk {$sale->buyer_name} berhasil dihapus dari sistem.");
}

    /**
     * Helper internal error response
     */
    private function errorResponse(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Data penjualan tidak bisa disimpan.',
                'errors' => ['quantity_sold_kg' => [$message]],
            ], 422);
        }
        return redirect()->back()->withInput()->withErrors(['quantity_sold_kg' => $message]);
    }

    public function deliveryInput(Sale $sale)
    {
        $sale->load(['partner', 'category']);
        if ($sale->status === 'sedang diproses') {
            $sale->update(['status' => 'dalam perjalanan']);
        }
        return view('sales.delivery-public', compact('sale'));
    }

    public function deliveryUpdate(Request $request, Sale $sale)
    {
        $request->validate([
            'delivery_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'delivery_proof.required' => 'Wajib mengambil atau mengunggah foto bukti pengiriman.',
            'delivery_proof.image' => 'File harus berupa gambar.',
            'delivery_proof.mimes' => 'Format gambar harus jpeg, png, atau jpg.'
        ]);

        if ($request->hasFile('delivery_proof')) {
            if ($sale->delivery_proof && Storage::disk('public')->exists('delivery_proofs/' . $sale->delivery_proof)) {
                Storage::disk('public')->delete('delivery_proofs/' . $sale->delivery_proof);
            }

            $file = $request->file('delivery_proof');
            $filename = 'proof_' . $sale->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('delivery_proofs', $filename, 'public');

            $sale->update([
                'delivery_proof' => $filename
            ]);

            return redirect()->back()->with('success', 'Bukti pengiriman berhasil diunggah! Menunggu konfirmasi selesai dari Owner.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah gambar.');
    }
}