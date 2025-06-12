<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderReportController extends Controller
{
    public function index(Request $request)
    {
        $reportType = $request->get('report_type', 'transactions');

        if ($reportType === 'products') {
            return $this->productReport($request);
        } elseif ($reportType === 'summary') {
            return $this->summaryReport($request);
        } else {
            return $this->transactionReport($request);
        }
    }

    protected function transactionReport(Request $request)
    {
        $query = DB::table('order_checkouts')
                ->join('orders', 'order_checkouts.order_id', '=', 'orders.id')
                ->join('checkouts', 'order_checkouts.checkout_id', '=', 'checkouts.id')
                ->join('users', 'checkouts.user_id', '=', 'users.id')->join('products', 'checkouts.product_id', '=', 'products.id')
                ->select('orders.id as order_id', 'orders.order_date', 'orders.unique_order_id', 'orders.payment_status', 'orders.order_status', 'orders.courier', 'orders.shipping_service', 'orders.tracking_number', 'orders.amount as order_total', 'orders.address as shipping_address',
                DB::raw('GROUP_CONCAT(products.name SEPARATOR ", ") as product_names'),
                DB::raw('SUM(checkouts.quantity) as total_quantity'), 'users.name as customer_name')->
                groupBy('orders.id', 'orders.order_date', 'orders.unique_order_id', 'orders.payment_status', 'orders.order_status', 'orders.courier', 'orders.shipping_service', 'orders.tracking_number', 'orders.amount', 'orders.address', 'users.name');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('orders.order_status', $request->status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('orders.order_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('tracking_number')) {
            $query->where('orders.tracking_number', 'like', '%' . $request->tracking_number . '%');
        }

        if ($request->filled('unique_order_id')) {
            $query->where('orders.unique_order_id', 'like', '%' . $request->unique_order_id . '%');
        }

        $transactions = $query->orderBy('orders.order_date', 'desc')->get();

        return view('reports.orders', [
            'transactions' => $transactions,
            'products' => collect(),
            'summary' => [],
        ]);
    }

    protected function summaryReport(Request $request)
    {
        $startDate = $request->filled('start_date') ? $request->start_date : now()->startOfMonth()->toDateString();
        $endDate = $request->filled('end_date') ? $request->end_date : now()->endOfMonth()->toDateString();


        $diffDays = Carbon::parse($startDate)->diffInDays($endDate);
        $previousStartDate = Carbon::parse($startDate)->subDays($diffDays)->toDateString();
        $previousEndDate = Carbon::parse($startDate)->subDay()->toDateString();

        // Summary periode saat ini
        $currentPeriod = DB::table('orders')
            ->selectRaw('SUM(amount) as total_revenue, COUNT(*) as total_transactions, AVG(amount) as average_order_value')
            ->selectRaw('SUM(amount) as total_revenue, COUNT(*) as total_transactions, AVG(amount) as average_order_value')
            ->whereBetween('order_date', [$startDate, $endDate])
            ->first();

        // Summary periode sebelumnya
        $currentProductsSold = DB::table('checkouts')
            ->join('order_checkouts', 'checkouts.id', '=', 'order_checkouts.checkout_id')
            ->join('orders', 'order_checkouts.order_id', '=', 'orders.id')
            ->whereBetween('orders.order_date', [$startDate, $endDate])
            ->sum('checkouts.quantity');

        // Summary periode sebelumnya
        $previousPeriod = DB::table('orders')
            ->selectRaw('SUM(amount) as total_revenue, COUNT(*) as total_transactions, AVG(amount) as average_order_value')
            ->selectRaw('SUM(amount) as total_revenue, COUNT(*) as total_transactions, AVG(amount) as average_order_value')
            ->whereBetween('order_date', [$previousStartDate, $previousEndDate])
            ->first();

        $previousProductsSold = DB::table('checkouts')
            ->join('order_checkouts', 'checkouts.id', '=', 'order_checkouts.checkout_id')
            ->join('orders', 'order_checkouts.order_id', '=', 'orders.id')
            ->whereBetween('orders.order_date', [$previousStartDate, $previousEndDate])
            ->sum('checkouts.quantity');

        // Persentase perubahan
        $revenueChange = $this->calculatePercentageChange($previousPeriod->total_revenue ?? 0, $currentPeriod->total_revenue ?? 0);
        $transactionsChange = $this->calculatePercentageChange($previousPeriod->total_transactions ?? 0, $currentPeriod->total_transactions ?? 0);
        $aovChange = $this->calculatePercentageChange($previousPeriod->average_order_value ?? 0, $currentPeriod->average_order_value ?? 0);
        $productsChange = $this->calculatePercentageChange($previousProductsSold ?? 0, $currentProductsSold ?? 0);
        $productsChange = $this->calculatePercentageChange($previousProductsSold ?? 0, $currentProductsSold ?? 0);

        // Breakdown berdasarkan status
        $statusBreakdown = DB::table('orders')
            ->select('order_status', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->whereBetween('order_date', [$startDate, $endDate])
            ->groupBy('order_status')
            ->get();

        // Breakdown berdasarkan kategori produk
        $categoryBreakdown = DB::table('products')
            ->leftJoin('checkouts', 'products.id', '=', 'checkouts.product_id')
            ->leftJoin('order_checkouts', 'checkouts.id', '=', 'order_checkouts.checkout_id')
            ->leftJoin('orders', 'order_checkouts.order_id', '=', 'orders.id')
            ->leftJoin('sub_category_products', 'products.sub_category_product_id', '=', 'sub_category_products.id')
            ->whereBetween('orders.order_date', [$startDate, $endDate])
            ->select(DB::raw('COALESCE(sub_category_products.name, "Uncategorized") as subCategory_name'), DB::raw('SUM(checkouts.quantity) as total_sold'), DB::raw('SUM(checkouts.amount) as total_revenue'))
            ->groupBy('sub_category_products.name')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        return view('reports.orders', [
            'transactions' => collect(),
            'products' => collect(),
            'summary' => [
                'total_revenue' => $currentPeriod->total_revenue ?? 0,
                'total_transactions' => $currentPeriod->total_transactions ?? 0,
                'average_order_value' => $currentPeriod->average_order_value ?? 0,
                'total_products_sold' => $currentProductsSold ?? 0,
                'total_products_sold' => $currentProductsSold ?? 0,
                'revenue_change' => $revenueChange,
                'transactions_change' => $transactionsChange,
                'aov_change' => $aovChange,
                'products_change' => $productsChange,
                'status_breakdown' => $statusBreakdown,
                'category_breakdown' => $categoryBreakdown,
            ],
        ]);
    }

    protected function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue == 0 ? 0 : 100;
        }

        return (($newValue - $oldValue) / $oldValue) * 100;
    }
}
