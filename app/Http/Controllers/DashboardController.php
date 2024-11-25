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
    public function index()
    {
        // Data yang dihitung
        $totalProducts = Product::count();
        $totalProductCategories = CategoryProduct::count();
        $totalBrands = Brand::count();
        $activeVouchers = Voucher::where('status', 'active')->count();

        // Data untuk grafik orders mingguan
        $weeklyOrders = Order::selectRaw('WEEK(created_at) as week, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        // Data untuk grafik pelanggan online mingguan (dari tabel users)
        $weeklyCustomers = User::selectRaw('WEEK(last_online_at) as week, COUNT(*) as count')
            ->whereYear('last_online_at', now()->year)
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalProductCategories',
            'totalBrands',
            'activeVouchers',
            'weeklyOrders',
            'weeklyCustomers'
        ));
    }
}
