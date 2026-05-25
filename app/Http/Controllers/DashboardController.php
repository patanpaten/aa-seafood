<?php

namespace App\Http\Controllers;

use App\Models\IncomingStock;
use App\Models\Sale;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // 1. Summary Cards (This Month)
        $thisMonthIncoming = IncomingStock::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('actual_weight');
        $thisMonthRevenue = Sale::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('total_price');
        $thisMonthShrinkage = IncomingStock::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('shrinkage_weight');
        
        // Total Stok Saat Ini (Global)
        $currentStock = Category::all()->sum(fn($cat) => $cat->current_stock);

        // 2. Monthly Drip Loss Trend (Last 6 Months)
        // Grouping data by Month and Year for the chart
        $dripLossTrend = IncomingStock::select(
                DB::raw('SUM(shrinkage_weight) as total_shrinkage'),
                DB::raw("DATE_FORMAT(date, '%M %Y') as month_year"),
                DB::raw('MAX(date) as latest_date')
            )
            ->where('date', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month_year')
            ->orderBy('latest_date', 'ASC')
            ->get();

        // 3. Best Selling Seafood (Based on Sales Volume)
        // Joining with categories table to get the name
        $bestSelling = Sale::select(
                'categories.name as category_name',
                DB::raw('SUM(sales.quantity_sold_kg) as total_qty')
            )
            ->join('categories', 'sales.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->orderBy('total_qty', 'DESC')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'thisMonthIncoming', 
            'thisMonthRevenue', 
            'thisMonthShrinkage', 
            'currentStock',
            'dripLossTrend',
            'bestSelling'
        ));
    }
}
