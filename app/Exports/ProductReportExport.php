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
        $query = Product::with(['subCategory', 'brand'])
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

        return $products->map(function ($product) {
            return [
                'Name' => $product->name,
                'Category' => $product->subCategory->name ?? '-',
                'Brand' => $product->brand->name ?? '-',
                'Price' => (float) $product->price,
                'Sales' => (int) $product->sales,
                'Wishlist' => (int) $product->wishlists_count,
                'Rating' => (float) $product->rating,
                'Review' => (int) $product->review_products_count,
            ];
        });
    }

    public function headings(): array
    {
        return ['Name', 'Category', 'Brand', 'Price', 'Sales', 'Wishlist', 'Rating', 'Review'];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'H' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
