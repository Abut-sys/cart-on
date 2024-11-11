<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\CategoryProduct;
use App\Models\Voucher;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalProducts' => Product::count(),
            'totalProductCategories' => CategoryProduct::count(),
            'totalBrands' => Brand::count(),
            'activeVouchers' => Voucher::where('status', 'active')->count(),
            // 'totalOrders' => Order::count(),
            // 'latestOrders' => Order::latest()->take(5)->get()
        ];

        return view('dashboard', $data);
    }
}
