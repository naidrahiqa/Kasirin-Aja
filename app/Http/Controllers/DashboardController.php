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
        // Build a full 7-day range first so empty days show as 0
        $last7Days = collect(range(6, 0))->map(fn($i) => [
            'date'  => Carbon::today()->subDays($i)->toDateString(),
            'total' => 0.0,
        ])->keyBy('date');

        $dbSales = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
            ->where('created_at', '>=', Carbon::today()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        // Merge DB results into the full 7-day range
        $weeklySales = $last7Days->map(function ($day) use ($dbSales) {
            if ($dbSales->has($day['date'])) {
                $day['total'] = (float) $dbSales[$day['date']]->total;
            }
            return $day;
        })->values();

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
