<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProductReportExport implements FromCollection, WithHeadings, WithColumnFormatting
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
        $query = Product::with(['subCategory', 'brand', 'subVariant'])
            ->withCount(['wishlists', 'reviewProducts']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', $this->search)
                    ->orWhere('name', 'like', "%{$this->search}%")
                    ->orWhere('price', 'like', "%{$this->search}%");
            });
        }

        if (in_array($this->sortColumn, ['id', 'name', 'price'])) {
            $query->orderBy($this->sortColumn, $this->sortDirection);
        }

        $products = $query->get();
        $rows = collect();

        foreach ($products as $product) {
            $first = true;
            foreach ($product->subVariant as $variant) {
                $rows->push([
                    'Name'     => $first ? $product->name : '',
                    'Category' => $first ? ($product->subCategory->name ?? '-') : '',
                    'Brand'    => $first ? ($product->brand->name ?? '-') : '',
                    'Price'    => $first ? $product->price : '',
                    'Sales'    => $first ? $product->sales : '',
                    'Wishlist' => $first ? $product->wishlists_count : '',
                    'Rating'   => $first ? $product->rating : '',
                    'Review'   => $first ? $product->review_products_count : '',
                    'Color'    => $variant->color,
                    'Size'     => $variant->size,
                    'Stock'    => $variant->stock,
                ]);
                $first = false;
            }

            if ($product->subVariant->isEmpty()) {
                $rows->push([
                    'Name'     => $product->name,
                    'Category' => $product->subCategory->name ?? '-',
                    'Brand'    => $product->brand->name ?? '-',
                    'Price'    => $product->price,
                    'Sales'    => $product->sales,
                    'Wishlist' => $product->wishlists_count,
                    'Rating'   => $product->rating,
                    'Review'   => $product->review_products_count,
                    'Color'    => '-',
                    'Size'     => '-',
                    'Stock'    => '-',
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Name', 'Category', 'Brand', 'Price', 'Sales', 'Wishlist', 'Rating', 'Review', 'Color', 'Size', 'Stock'];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'H' => NumberFormat::FORMAT_NUMBER,
            'K' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
