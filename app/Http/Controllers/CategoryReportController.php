<?php

namespace App\Http\Controllers;

use App\Exports\CategoryReportExport;
use App\Models\CategoryProduct;
use App\Models\SubCategoryProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CategoryReportController extends Controller
{
    public function index(Request $request)
    {
        $searchable = ['id', 'name'];
        $query = CategoryProduct::with('subCategories');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id', $request->search)
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhereHas('subCategories', function ($sub) use ($request) {
                        $sub->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $sortColumn = $request->input('sort_column', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        if (in_array($sortColumn, $searchable) && in_array($sortDirection, ['asc', 'desc'])) {
            $query->orderBy($sortColumn, $sortDirection);
        } elseif ($sortColumn === 'sub_category' && in_array($sortDirection, ['asc', 'desc'])) {
            $query->orderBy(
                SubCategoryProduct::select('name')
                    ->whereColumn('category_product_id', 'category_products.id')
                    ->orderBy('name')
                    ->limit(1),
                $sortDirection
            );
        }

        $categories = $query->paginate(5)->withQueryString();

        return view('reports.categories', compact('categories'));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new CategoryReportExport(
            $request->input('sort_column', 'id'),
            $request->input('sort_direction', 'asc'),
            $request->input('search')
        ), 'category-report.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = CategoryProduct::with(['subCategories']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhereHas('subCategories', fn($sub) => $sub->where('name', 'like', "%{$search}%"));
            });
        }

        $sortColumn = $request->input('sort_column', 'id');
        $sortDirection = $request->input('sort_direction') ?? ($request->filled('search') ? 'desc' : 'asc');

        if (in_array($sortColumn, ['id', 'name']) && in_array($sortDirection, ['asc', 'desc'])) {
            $query->orderBy($sortColumn, $sortDirection);
        } elseif ($sortColumn === 'sub_category' && in_array($sortDirection, ['asc', 'desc'])) {
            $query->orderBy(
                SubCategoryProduct::select('name')
                    ->whereColumn('category_product_id', 'category_products.id')
                    ->orderBy('name')
                    ->limit(1),
                $sortDirection
            );
        }

        $categories = $query->get();

        $pdf = Pdf::loadView('reports.category-pdf', compact('categories'))
            ->setPaper('A4', 'landscape');

        return $request->input('mode') === 'preview'
            ? $pdf->stream('category-report.pdf')
            : $pdf->download('category-report.pdf');
    }
}
