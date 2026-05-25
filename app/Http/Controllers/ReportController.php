<?php

namespace App\Http\Controllers;

use App\Models\IncomingStock;
use App\Models\Sale;
use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the reporting dashboard with summary data.
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        $reportData = $this->getAggregatedData($startDate, $endDate);

        return view('reports.index', compact('reportData', 'startDate', 'endDate'));
    }

    /**
     * Export the filtered report to PDF.
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $reportData = $this->getAggregatedData($startDate, $endDate);
        
        $pdf = Pdf::loadView('reports.pdf', [
            'reportData' => $reportData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => Carbon::now()->format('d/m/Y H:i')
        ]);

        return $pdf->download("Laporan_Seafood_{$startDate}_ke_{$endDate}.pdf");
    }

    /**
     * Helper to aggregate stock and sales data.
     */
    private function getAggregatedData($startDate, $endDate)
    {
        // 1. Total Incoming Seafood (kg)
        $totalIncoming = IncomingStock::whereBetween('date', [$startDate, $endDate])->sum('actual_weight');

        // 2. Total Shrinkage / Drip Loss (kg)
        $totalShrinkage = IncomingStock::whereBetween('date', [$startDate, $endDate])->sum('shrinkage_weight');

        // 3. Total Sales (kg & Revenue)
        $salesQuery = Sale::whereBetween('date', [$startDate, $endDate]);
        $totalSalesKg = $salesQuery->sum('quantity_sold_kg');
        $totalRevenue = $salesQuery->sum('total_price');

        // 4. Current Available Stock
        $currentStock = Category::all()->sum(fn($cat) => $cat->current_stock);

        // Breakdown by Category
        $categories = Category::orderBy('name')->get();
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
}
