<?php

namespace App\Http\Controllers;

use App\Exports\BrandReportExport;
use App\Models\Brand;
use App\Models\CategoryProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BrandReportController extends Controller
{
    public function index(Request $request)
    {
        $searchable = ['id', 'name'];
        $query = Brand::with('categoryProduct');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id', $request->search)
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhereHas('categoryProduct', function ($sub) use ($request) {
                        $sub->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $sortColumn = $request->input('sort_column', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        if (in_array($sortColumn, $searchable) && in_array($sortDirection, ['asc', 'desc'])) {
            $query->orderBy($sortColumn, $sortDirection);
        } elseif ($sortColumn === 'category' && in_array($sortDirection, ['asc', 'desc'])) {
            $query->orderBy(
                CategoryProduct::select('name')
                    ->whereColumn('category_products.id', 'brands.category_product_id')
                    ->limit(1),
                $sortDirection
            );
        }

        $brands = $query->paginate(5)->withQueryString();

        return view('reports.brands', compact('brands'));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new BrandReportExport(
            $request->input('sort_column', 'id'),
            $request->input('sort_direction', 'asc'),
            $request->input('search')
        ), 'brand-report.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = Brand::with('categoryProduct');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhereHas('categoryProduct', fn($sub) => $sub->where('name', 'like', "%{$search}%"));
            });
        }

        $sortColumn = $request->input('sort_column', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        if (in_array($sortColumn, ['id', 'name']) && in_array($sortDirection, ['asc', 'desc'])) {
            $query->orderBy($sortColumn, $sortDirection);
        } elseif ($sortColumn === 'category' && in_array($sortDirection, ['asc', 'desc'])) {
            $query->orderBy(
                CategoryProduct::select('name')
                    ->whereColumn('category_products.id', 'brands.category_product_id')
                    ->limit(1),
                $sortDirection
            );
        }

        $brands = $query->get();

        $pdf = Pdf::loadView('reports.brands-pdf', compact('brands'))
            ->setPaper('A4', 'landscape');

        return $request->input('mode') === 'preview'
            ? $pdf->stream('brand-report.pdf')
            : $pdf->download('brand-report.pdf');
    }
}
