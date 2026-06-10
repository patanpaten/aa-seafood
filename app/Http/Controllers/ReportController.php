<?php

namespace App\Http\Controllers;

use App\Models\IncomingStock;
use App\Models\Sale;
use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * Display the reporting dashboard with summary data.
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());
        
        $selectedGroup = $request->input('group');
        $selectedCategory = $request->input('category_id'); // BARU: Tangkap input barang
        
        $normalizedGroup = $this->normalizeGroup($selectedGroup);
        
        $categoryGroups = Category::query()
            ->whereNotNull('group_name')
            ->get()
            ->pluck('display_group_name')
            ->unique()
            ->sort()
            ->values();

        // BARU: Ambil semua data barang untuk dropdown filter
        $categoriesList = Category::orderBy('name')->get();

        // BARU: Passing selectedCategory ke fungsi agregat
        $reportData = $this->getAggregatedData($startDate, $endDate, $normalizedGroup, $selectedCategory);

        // BARU: Tambahkan categoriesList dan selectedCategory ke view (compact)
        return view('reports.index', compact('reportData', 'startDate', 'endDate', 'categoryGroups', 'selectedGroup', 'categoriesList', 'selectedCategory'));
    }

    /**
     * Export the filtered report to PDF.
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $selectedGroup = $request->input('group');
        $selectedCategory = $request->input('category_id'); // BARU
        
        $normalizedGroup = $this->normalizeGroup($selectedGroup);

        // BARU: Passing selectedCategory ke fungsi agregat
        $reportData = $this->getAggregatedData($startDate, $endDate, $normalizedGroup, $selectedCategory);
        
        $pdf = Pdf::loadView('reports.pdf', [
            'reportData' => $reportData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedGroup' => $selectedGroup,
            'selectedCategory' => $selectedCategory, // BARU
            'generatedAt' => Carbon::now()->format('d/m/Y H:i')
        ]);

        return $pdf->download("Laporan_Seafood_{$startDate}_ke_{$endDate}.pdf");
    }

    /**
     * Helper to aggregate stock and sales data.
     */
    private function getAggregatedData($startDate, $endDate, ?string $selectedGroup = null, ?string $selectedCategory = null) // BARU: Tambah parameter selectedCategory
    {
        // BARU: Ambil ID kategori dengan mengecek filter grup DAN/ATAU filter barang
        $categoryIds = Category::query()
            ->when($selectedGroup, fn ($query) => $query->whereRaw('LOWER(TRIM(group_name)) = ?', [$selectedGroup]))
            ->when($selectedCategory, fn ($query) => $query->where('id', $selectedCategory)) // Filter barang spesifik
            ->pluck('id');

        // BARU: Query diperbarui untuk selalu memakai whereIn category_id
        $incomingQuery = IncomingStock::query()
            ->with(['supplier', 'category'])
            ->whereBetween('date', [$startDate, $endDate])
            ->whereIn('category_id', $categoryIds);

        // BARU: Query diperbarui untuk selalu memakai whereIn category_id
        $salesQuery = Sale::query()
            ->with(['partner', 'category'])
            ->whereBetween('date', [$startDate, $endDate])
            ->whereIn('category_id', $categoryIds);

        // 1. Total Incoming Seafood (kg)
        $totalIncoming = (clone $incomingQuery)
            ->sum('actual_weight');

        // 2. Total Shrinkage / Drip Loss (kg)
        $totalShrinkage = (clone $incomingQuery)
            ->sum('shrinkage_weight');

        // 3. Total Purchase Cost
        $totalPurchaseCost = (clone $incomingQuery)->sum('total_purchase_price');

        // 4. Total Sales (kg & Revenue)
        $totalSalesKg = (clone $salesQuery)->sum('quantity_sold_kg');
        $totalRevenue = (clone $salesQuery)->sum('total_price');
        $grossProfit = $totalRevenue - $totalPurchaseCost;

        // 5. Current Available Stock
        $categories = Category::query()
            ->whereIn('id', $categoryIds) // BARU: sesuaikan pencarian stok sesuai yang terfilter
            ->orderBy('name')
            ->get();
        $currentStock = $categories->sum(fn ($cat) => $cat->current_stock);

        $incomingDetails = (clone $incomingQuery)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get()
            ->map(function ($incoming) {
                return [
                    'date' => optional($incoming->date)->format('d/m/Y'),
                    'supplier_name' => $incoming->supplier?->name ?? 'Tempat beli tidak diketahui',
                    'group_name' => $incoming->category?->display_group_name ?? '-',
                    'category_name' => $incoming->category?->name ?? '-',
                    'quantity' => (float) $incoming->actual_weight,
                    'purchase_price_per_kg' => (float) $incoming->purchase_price_per_kg,
                    'total_purchase_price' => (float) $incoming->total_purchase_price,
                    'receipt_weight' => (float) $incoming->receipt_weight,
                    'shrinkage_weight' => (float) $incoming->shrinkage_weight,
                ];
            })
            ->values()
            ->all();

        $salesDetails = (clone $salesQuery)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get()
            ->map(function ($sale) {
                return [
                    'date' => optional($sale->date)->format('d/m/Y'),
                    'buyer_name' => $sale->display_buyer_name,
                    'group_name' => $sale->category?->display_group_name ?? '-',
                    'category_name' => $sale->category?->name ?? '-',
                    'quantity' => (float) $sale->quantity_sold_kg,
                    'sale_price_per_kg' => (float) $sale->price_per_kg,
                    'total_price' => (float) $sale->total_price,
                ];
            })
            ->values()
            ->all();

        $activityDetails = collect($incomingDetails)
            ->map(function ($item) {
                return [
                    'date' => $item['date'],
                    'type' => 'Barang Masuk',
                    'party_name' => $item['supplier_name'],
                    'group_name' => $item['group_name'],
                    'category_name' => $item['category_name'],
                    'quantity' => $item['quantity'],
                    'purchase_price_per_kg' => $item['purchase_price_per_kg'],
                    'sale_price_per_kg' => null,
                    'total_price' => $item['total_purchase_price'],
                    'sort_date' => Carbon::createFromFormat('d/m/Y', $item['date'])->format('Y-m-d'),
                ];
            })
            ->merge(collect($salesDetails)->map(function ($item) {
                return [
                    'date' => $item['date'],
                    'type' => 'Penjualan',
                    'party_name' => $item['buyer_name'],
                    'group_name' => $item['group_name'],
                    'category_name' => $item['category_name'],
                    'quantity' => $item['quantity'],
                    'purchase_price_per_kg' => null,
                    'sale_price_per_kg' => $item['sale_price_per_kg'],
                    'total_price' => $item['total_price'],
                    'sort_date' => Carbon::createFromFormat('d/m/Y', $item['date'])->format('Y-m-d'),
                ];
            }))
            ->sortByDesc(fn ($item) => $item['sort_date'] . '|' . $item['type'])
            ->values()
            ->map(function ($item) {
                unset($item['sort_date']);

                return $item;
            })
            ->all();

        // Breakdown by Category
        $breakdown = [];

        foreach ($categories as $category) {
            $incoming = IncomingStock::where('category_id', $category->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('actual_weight');
                
            $sales = Sale::where('category_id', $category->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('quantity_sold_kg');

            $breakdown[] = [
                'type' => $category->name,
                'group' => $category->display_group_name,
                'incoming' => $incoming,
                'sales' => $sales,
                'current_stock' => $category->current_stock
            ];
        }

        return [
            'total_incoming' => $totalIncoming,
            'total_shrinkage' => $totalShrinkage,
            'total_purchase_cost' => $totalPurchaseCost,
            'total_sales_kg' => $totalSalesKg,
            'total_revenue' => $totalRevenue,
            'gross_profit' => $grossProfit,
            'current_stock' => $currentStock,
            'breakdown' => $breakdown,
            'incoming_details' => $incomingDetails,
            'sales_details' => $salesDetails,
            'activity_details' => $activityDetails,
        ];
    }

    private function normalizeGroup(?string $group): ?string
    {
        if (! $group) {
            return null;
        }

        return Str::lower(Str::squish($group));
    }
}