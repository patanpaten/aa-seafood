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
        $normalizedGroup = $this->normalizeGroup($selectedGroup);
        $categoryGroups = Category::query()
            ->whereNotNull('group_name')
            ->get()
            ->pluck('display_group_name')
            ->unique()
            ->sort()
            ->values();

        $reportData = $this->getAggregatedData($startDate, $endDate, $normalizedGroup);

        return view('reports.index', compact('reportData', 'startDate', 'endDate', 'categoryGroups', 'selectedGroup'));
    }

    /**
     * Export the filtered report to PDF.
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $selectedGroup = $request->input('group');
        $normalizedGroup = $this->normalizeGroup($selectedGroup);

        $reportData = $this->getAggregatedData($startDate, $endDate, $normalizedGroup);
        
        $pdf = Pdf::loadView('reports.pdf', [
            'reportData' => $reportData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedGroup' => $selectedGroup,
            'generatedAt' => Carbon::now()->format('d/m/Y H:i')
        ]);

        return $pdf->download("Laporan_Seafood_{$startDate}_ke_{$endDate}.pdf");
    }

    /**
     * Helper to aggregate stock and sales data.
     */
    private function getAggregatedData($startDate, $endDate, ?string $selectedGroup = null)
    {
        $categoryIds = Category::query()
            ->when($selectedGroup, fn ($query) => $query->whereRaw('LOWER(TRIM(group_name)) = ?', [$selectedGroup]))
            ->pluck('id');

        // 1. Total Incoming Seafood (kg)
        $totalIncoming = IncomingStock::whereBetween('date', [$startDate, $endDate])
            ->when($selectedGroup, fn ($query) => $query->whereIn('category_id', $categoryIds))
            ->sum('actual_weight');

        // 2. Total Shrinkage / Drip Loss (kg)
        $totalShrinkage = IncomingStock::whereBetween('date', [$startDate, $endDate])
            ->when($selectedGroup, fn ($query) => $query->whereIn('category_id', $categoryIds))
            ->sum('shrinkage_weight');

        // 3. Total Sales (kg & Revenue)
        $salesQuery = Sale::whereBetween('date', [$startDate, $endDate])
            ->when($selectedGroup, fn ($query) => $query->whereIn('category_id', $categoryIds));
        $totalSalesKg = $salesQuery->sum('quantity_sold_kg');
        $totalRevenue = $salesQuery->sum('total_price');

        // 4. Current Available Stock
        $categories = Category::query()
            ->when($selectedGroup, fn ($query) => $query->whereIn('id', $categoryIds))
            ->orderBy('name')
            ->get();
        $currentStock = $categories->sum(fn ($cat) => $cat->current_stock);

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
            'total_sales_kg' => $totalSalesKg,
            'total_revenue' => $totalRevenue,
            'current_stock' => $currentStock,
            'breakdown' => $breakdown
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
