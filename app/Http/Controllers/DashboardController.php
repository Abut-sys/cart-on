<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\CategoryProduct;
use App\Models\Voucher;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    // Data yang dihitung
    $totalProducts = Product::count();
    $totalProductCategories = CategoryProduct::count();
    $totalBrands = Brand::count();
    $activeVouchers = Voucher::where('status', 'active')->count();

    // Hitung jumlah pesanan per minggu selama 6 minggu terakhir
    $weeklyOrders = Order::selectRaw('YEARWEEK(created_at, 1) as week, COUNT(*) as count')
        ->where('created_at', '>=', Carbon::now()->subWeeks(6)->startOfWeek())
        ->groupBy('week')
        ->orderBy('week')
        ->get()
        ->map(function ($order) {
            return [
                'week' => $order->week,
                'count' => $order->count,
            ];
        });

    return view('dashboard', [
        'totalProducts' => $totalProducts,
        'totalProductCategories' => $totalProductCategories,
        'totalBrands' => $totalBrands,
        'activeVouchers' => $activeVouchers,
        'weeklyOrders' => $weeklyOrders,
    ]);
}

}
