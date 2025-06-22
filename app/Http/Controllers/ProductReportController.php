<?php

namespace App\Http\Controllers;

use App\Exports\ProductReportExport;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductReportController extends Controller
{
    public function index(Request $request)
    {
        $searchable = ['id', 'name', 'price'];
        $sortableRelations = ['sub_category' => 'subCategory.name', 'brand' => 'brand.name'];

        $query = Product::with(['brand', 'subCategory'])
            ->withCount(['checkouts', 'carts', 'wishlists', 'reviewProducts']);

        $isFiltered = $request->filled('search');

        if ($isFiltered) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('name', 'like', "%$search%")
                    ->orWhere('price', 'like', "%$search%")
                    ->orWhereHas('subCategory', fn($q) => $q->where('name', 'like', "%$search%"))
                    ->orWhereHas('brand', fn($q) => $q->where('name', 'like', "%$search%"));
            });
        }

        $sortColumn = $request->input('sort_column', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        if (in_array($sortDirection, ['asc', 'desc'])) {
            if (in_array($sortColumn, $searchable)) {
                $query->orderBy("products.$sortColumn", $sortDirection);
            } elseif ($sortColumn === 'sub_category') {
                $query->leftJoin('sub_category_products', 'products.sub_category_product_id', '=', 'sub_category_products.id')
                    ->orderBy('sub_category_products.name', $sortDirection)
                    ->select('products.*');
            } elseif ($sortColumn === 'brand') {
                $query->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                    ->orderBy('brands.name', $sortDirection)
                    ->select('products.*');
            }
        }

        $products = $query->paginate(10)->withQueryString();

        return view('reports.products', compact('products'));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new ProductReportExport(
            $request->input('sort_column', 'id'),
            $request->input('sort_direction', 'asc'),
            $request->input('search')
        ), 'product-report.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = Product::with(['brand', 'subCategory'])
            ->withCount(['checkouts', 'wishlists', 'reviewProducts']);

        $isFiltered = $request->filled('search');

        if ($isFiltered) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('name', 'like', "%$search%")
                    ->orWhere('price', 'like', "%$search%")
                    ->orWhereHas('subCategory', fn($q) => $q->where('name', 'like', "%$search%"))
                    ->orWhereHas('brand', fn($q) => $q->where('name', 'like', "%$search%"));
            });
        }

        $sortColumn = $request->input('sort_column', 'id');
        $sortDirection = $request->input('sort_direction') ?? ($isFiltered ? 'desc' : 'asc');

        if (in_array($sortDirection, ['asc', 'desc'])) {
            if (in_array($sortColumn, ['id', 'name', 'price'])) {
                $query->orderBy($sortColumn, $sortDirection);
            } elseif ($sortColumn === 'sub_category') {
                $query->join('sub_category_products', 'products.sub_category_product_id', '=', 'sub_category_products.id')
                    ->orderBy('sub_category_products.name', $sortDirection)
                    ->select('products.*');
            } elseif ($sortColumn === 'brand') {
                $query->join('brands', 'products.brand_id', '=', 'brands.id')
                    ->orderBy('brands.name', $sortDirection)
                    ->select('products.*');
            }
        }

        $products = $query->get();

        $pdf = Pdf::loadView('reports.products-pdf', compact('products'))
            ->setPaper('A4', 'landscape');

        return $request->input('mode') === 'preview'
            ? $pdf->stream('product-report.pdf')
            : $pdf->download('product-report.pdf');
    }
}
