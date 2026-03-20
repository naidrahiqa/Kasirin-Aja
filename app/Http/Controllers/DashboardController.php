<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the analytics dashboard.
     *
     * Metrics:
     * - Total sales amount today
     * - Total transaction count today
     * - Total products count
     * - Total categories count
     * - 5 most recent transactions
     * - Sales grouped by day for the last 7 days (for chart)
     * - Top 5 best-selling products
     */
    public function index(): View
    {
        $today = Carbon::today();

        // Total sales amount today
        $todaySales = Transaction::whereDate('created_at', $today)
            ->sum('total_amount');

        // Total transaction count today
        $todayTransactionCount = Transaction::whereDate('created_at', $today)
            ->count();

        // Total products and categories
        $totalProducts = Product::count();
        $totalCategories = Category::count();

        // 5 most recent transactions
        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Sales grouped by day for the last 7 days (for chart data)
        $weeklySales = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'total' => (float) $item->total,
                ];
            });

        // Top 5 best-selling products (by quantity sold)
        $topProducts = DB::table('transaction_details')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(transaction_details.quantity) as total_sold'), DB::raw('SUM(transaction_details.subtotal) as total_revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Monthly revenue (current month)
        $monthlyRevenue = Transaction::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        return view('dashboard', compact(
            'todaySales',
            'todayTransactionCount',
            'totalProducts',
            'totalCategories',
            'recentTransactions',
            'weeklySales',
            'topProducts',
            'monthlyRevenue'
        ));
    }
}
