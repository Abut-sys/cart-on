<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Models\CategoryProduct;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Data yang dihitung
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('amount');
        $totalProducts = Product::count();
        $topProducts = Product::select('products.id', 'products.name')
            ->selectRaw('SUM(checkouts.quantity) as total_sold')
            ->join('checkouts', 'checkouts.product_id', '=', 'products.id')
            ->join('order_checkouts', 'order_checkouts.checkout_id', '=', 'checkouts.id')
            ->join('orders', 'orders.id', '=', 'order_checkouts.order_id')
            ->where('orders.payment_status', 'completed')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(3)
            ->get();
        $totalBrands = Brand::count();
        $activeVouchers = Voucher::where('status', 'active')->count();

        // Get available years for filter
        $availableYears = Order::selectRaw('YEAR(created_at) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Default to current year if no years exist
        if (empty($availableYears)) {
            $availableYears = [now()->year];
        }

        // Get selected year and month from request or use current
        $selectedYear = $request->input('year', date('Y'));
        $selectedMonth = $request->input('month', 0); // 0 means all months

        // Data untuk grafik orders berdasarkan hari dalam minggu
        $weekdayQuery = Order::selectRaw('DAYOFWEEK(created_at) - 1 as day_of_week, COUNT(*) as count')
            ->whereYear('created_at', $selectedYear);

        if ($selectedMonth > 0) {
            $weekdayQuery->whereMonth('created_at', $selectedMonth);
        }

        $weekdayOrders = $weekdayQuery->groupBy('day_of_week')
            ->orderBy('day_of_week')
            ->get();

        // Ensure all days are represented (0-6)
        $allDays = collect();
        for ($day = 0; $day < 7; $day++) {
            $found = $weekdayOrders->firstWhere('day_of_week', $day);
            $allDays->push($found ?: (object)['day_of_week' => $day, 'count' => 0]);
        }

        // Data untuk daftar pelanggan online terakhir (10 terbaru)
        $recentCustomers = User::with('profile')
            ->whereNotNull('last_online_at')
            ->orderBy('last_online_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'topProducts',
            'totalBrands',
            'activeVouchers',
            'weekdayOrders',
            'recentCustomers',
            'availableYears',
            'selectedYear',
            'selectedMonth'
        ));
    }
}
