<?php

namespace App\Exports;

use App\Models\Voucher;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VoucherReportExport implements FromCollection, WithHeadings
{
    protected ?string $type;
    protected ?string $status;
    protected ?string $search;
    protected ?string $startDate;
    protected ?string $endDate;
    protected string $sortColumn;
    protected string $sortDirection;

    public function __construct(
        $type = null,
        $status = null,
        $search = null,
        $startDate = null,
        $endDate = null,
        $sortColumn = 'id',
        $sortDirection = 'asc'
    ) {
        $this->type = $type;
        $this->status = $status;
        $this->search = $search;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->sortColumn = $sortColumn;
        $this->sortDirection = $sortDirection;
    }

    public function collection(): Collection
    {
        $query = Voucher::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('code', 'like', "%{$this->search}%")
                  ->orWhere('terms_and_conditions', 'like', "%{$this->search}%");
            });
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->startDate) {
            $query->whereDate('start_date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('end_date', '<=', $this->endDate);
        }

        // Sorting
        if (in_array($this->sortColumn, ['id', 'code', 'start_date', 'end_date', 'usage_limit']) ||
            ($this->sortColumn === 'discount_value' && $this->type)) {
            $query->orderBy($this->sortColumn, $this->sortDirection);
        }

        $vouchers = $query->get();

        return $vouchers->map(function ($voucher) {
            return [
                'No'        => $voucher->id,
                'Code'      => $voucher->code,
                'Start Date'=> $voucher->start_date->format('Y-m-d'),
                'End Date'  => $voucher->end_date->format('Y-m-d'),
                'Limit'     => $voucher->usage_limit,
                'Discount'  => $voucher->type === 'percentage'
                    ? $voucher->discount_value . '%'
                    : 'Rp ' . number_format($voucher->discount_value, 0, ',', '.'),
                'Type'      => ucfirst($voucher->type),
                'Terms'     => $voucher->terms_and_conditions,
                'Status'    => ucfirst($voucher->status),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Code',
            'Start Date',
            'End Date',
            'Limit',
            'Discount',
            'Type',
            'Terms',
            'Status',
        ];
    }
}
