<?php

namespace App\Http\Controllers;

use App\Exports\VoucherReportExport;
use App\Models\Voucher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class VoucherReportController extends Controller
{
    public function index(Request $request)
    {
        $searchable = ['id', 'code', 'start_date', 'end_date', 'usage_limit'];

        $query = Voucher::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%$search%")
                    ->orWhere('type', 'like', "%$search%")
                    ->orWhere('terms_and_conditions', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('discount_value', 'like', "%$search%")
                    ->orWhere('usage_limit', 'like', "%$search%")
                    ->orWhereDate('start_date', $search)
                    ->orWhereDate('end_date', $search);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
            $searchable[] = 'discount_value';
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $sortColumn = $request->input('sort_column', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        if (in_array($sortColumn, $searchable) && in_array($sortDirection, ['asc', 'desc'])) {
            $query->orderBy($sortColumn, $sortDirection);
        }

        $vouchers = $query->paginate(10)->withQueryString();

        return view('reports.vouchers', compact('vouchers'));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new VoucherReportExport(
            $request->type,
            $request->status,
            $request->search,
            $request->start_date,
            $request->end_date,
            $request->input('sort_column', 'id'),
            $request->input('sort_direction', 'asc')
        ), 'voucher-report.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $searchable = ['id', 'code', 'start_date', 'end_date', 'usage_limit'];
        $query = Voucher::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%$search%")
                    ->orWhere('type', 'like', "%$search%")
                    ->orWhere('terms_and_conditions', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('discount_value', 'like', "%$search%")
                    ->orWhere('usage_limit', 'like', "%$search%")
                    ->orWhereDate('start_date', $search)
                    ->orWhereDate('end_date', $search);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
            $searchable[] = 'discount_value';
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $sortColumn = $request->input('sort_column', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        if (in_array($sortColumn, $searchable) && in_array($sortDirection, ['asc', 'desc'])) {
            $query->orderBy($sortColumn, $sortDirection);
        }

        $vouchers = $query->get();

        $pdf = Pdf::loadView('reports.vouchers-pdf', compact('vouchers'))
            ->setPaper('A4', 'landscape');

        return $request->input('mode') === 'preview'
            ? $pdf->stream('voucher-report.pdf')
            : $pdf->download('voucher-report.pdf');
    }
}
