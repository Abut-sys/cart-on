<?php

namespace App\Http\Controllers;

use App\Exports\ProductReportExport;
use App\Models\Brand;
use App\Models\Product;
use App\Models\SubCategoryProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductReportController extends Controller
{
    public function index(Request $request)
    {
        $searchable = ['id', 'name', 'price'];
        $query = Product::with(['brand', 'subCategory'])
            ->withCount(['wishlists', 'reviewProducts']);

        if ($request->filled('search')) {
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
                $query->orderBy(
                    SubCategoryProduct::select('name')->whereColumn('sub_category_products.id', 'products.sub_category_product_id'),
                    $sortDirection
                );
            } elseif ($sortColumn === 'brand') {
                $query->orderBy(
                    Brand::select('name')->whereColumn('brands.id', 'products.brand_id'),
                    $sortDirection
                );
            } elseif (in_array($sortColumn, ['sales', 'rating', 'wishlists_count', 'review_products_count'])) {
                $query->orderBy($sortColumn, $sortDirection);
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
            ->withCount(['wishlists', 'reviewProducts']);

        if ($request->filled('search')) {
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
        $sortDirection = $request->input('sort_direction') ?? ($request->filled('search') ? 'desc' : 'asc');

        if (in_array($sortDirection, ['asc', 'desc'])) {
            switch ($sortColumn) {
                case 'id':
                case 'name':
                case 'price':
                    $query->orderBy("products.$sortColumn", $sortDirection);
                    break;

                case 'sub_category':
                    $query->orderBy(
                        SubCategoryProduct::select('name')
                            ->whereColumn('sub_category_products.id', 'products.sub_category_product_id'),
                        $sortDirection
                    );
                    break;

                case 'brand':
                    $query->orderBy(
                        Brand::select('name')
                            ->whereColumn('brands.id', 'products.brand_id'),
                        $sortDirection
                    );
                    break;

                case 'sales':
                case 'rating':
                case 'wishlists_count':
                case 'review_products_count':
                    $query->orderBy($sortColumn, $sortDirection);
                    break;
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
