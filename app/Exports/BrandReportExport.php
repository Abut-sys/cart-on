<?php

namespace App\Exports;

use App\Models\Brand;
use App\Models\CategoryProduct;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BrandReportExport implements FromCollection, WithHeadings
{
    protected string $sortColumn;
    protected string $sortDirection;
    protected ?string $search;

    public function __construct($sortColumn = 'id', $sortDirection = 'asc', $search = null)
    {
        $this->sortColumn = $sortColumn;
        $this->sortDirection = $sortDirection;
        $this->search = $search;
    }

    public function collection(): Collection
    {
        $query = Brand::with('categoryProduct');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', $this->search)
                    ->orWhere('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('categoryProduct', fn($sub) => $sub->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        if (in_array($this->sortColumn, ['id', 'name']) && in_array($this->sortDirection, ['asc', 'desc'])) {
            $query->orderBy($this->sortColumn, $this->sortDirection);
        } elseif ($this->sortColumn === 'category' && in_array($this->sortDirection, ['asc', 'desc'])) {
            $query->orderBy(
                CategoryProduct::select('name')
                    ->whereColumn('category_products.id', 'brands.category_product_id')
                    ->limit(1),
                $this->sortDirection
            );
        }

        $brands = $query->get();

        $rows = collect();

        foreach ($brands as $brand) {
            $rows->push([
                'Brand Name'    => $brand->name,
                'Category Name' => $brand->categoryProduct->name ?? '-',
            ]);
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Brand Name', 'Category Name'];
    }
}
