<?php

namespace App\Exports;

use App\Models\CategoryProduct;
use App\Models\SubCategoryProduct;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoryReportExport implements FromCollection, WithHeadings
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
        $query = CategoryProduct::with('subCategories');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', $this->search)
                    ->orWhere('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('subCategories', fn($sub) => $sub->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        if (in_array($this->sortColumn, ['id', 'name']) && in_array($this->sortDirection, ['asc', 'desc'])) {
            $query->orderBy($this->sortColumn, $this->sortDirection);
        } elseif ($this->sortColumn === 'sub_category' && in_array($this->sortDirection, ['asc', 'desc'])) {
            $query->orderBy(
                SubCategoryProduct::select('name')
                    ->whereColumn('category_product_id', 'category_products.id')
                    ->orderBy('name')
                    ->limit(1),
                $this->sortDirection
            );
        }

        $categories = $query->get();

        $rows = collect();

        foreach ($categories as $category) {
            $first = true;
            foreach ($category->subCategories as $sub) {
                $rows->push([
                    'Category Name' => $first ? $category->name : '',
                    'Sub Category'  => $sub->name,
                ]);
                $first = false;
            }

            if ($first) {
                $rows->push([
                    'Category Name' => $category->name,
                    'Sub Category'  => '-',
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Category Name', 'Sub Category'];
    }
}
