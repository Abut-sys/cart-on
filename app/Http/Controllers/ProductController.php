<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\SubCategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $searchable = ['id', 'name', 'price', 'old_price', 'sales', 'rating'];
        $variantSearchable = ['color', 'size', 'stock'];

        $query = Product::with(['subCategory', 'brand', 'subVariant', 'images']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request, $variantSearchable) {
                $q->where('id', $request->search)
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('price', 'like', '%' . $request->search . '%')
                    ->orWhere('old_price', 'like', '%' . $request->search . '%')
                    ->orWhereHas('subCategory', fn($sub) => $sub->where('name', 'like', '%' . $request->search . '%'))
                    ->orWhereHas('brand', fn($brand) => $brand->where('name', 'like', '%' . $request->search . '%'));

                foreach ($variantSearchable as $field) {
                    $q->orWhereHas('subVariant', fn($variant) => $variant->where($field, 'like', '%' . $request->search . '%'));
                }
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
            } elseif (in_array($sortColumn, $variantSearchable)) {
                $query->leftJoin('sub_variants', 'products.id', '=', 'sub_variants.product_id')
                    ->orderBy("sub_variants.$sortColumn", $sortDirection)
                    ->select('products.*');
            }
        }

        $products = $query->paginate(10)->withQueryString();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $subcategories = SubCategoryProduct::all();
        $brands = Brand::all();

        return view('products.create', compact('subcategories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sub_category_product_id' => 'required|exists:sub_category_products,id',
            'brand_id' => 'required|exists:brands,id',
            'variants' => 'required|array',
            'variants.*.color' => 'required|string',
            'variants.*.size' => 'required|string',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        $markupPercentage = $request->markup;
        $markupAmount = ($request->price * $markupPercentage) / 100;
        $oldPrice = $request->price + $markupAmount;

        $ppnAmount = ($oldPrice * 11) / 100;
        $finalPrice = $oldPrice + $ppnAmount;

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'markup' => $markupPercentage,
            'old_price' => $oldPrice,
            'price' => $finalPrice,
            'sub_category_product_id' => $request->sub_category_product_id,
            'brand_id' => $request->brand_id,
        ]);

        foreach ($request->variants as $variant) {
            $product->subVariant()->create($variant);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('product_images', 'public');
                $product->images()->create([
                    'image_path' => $imagePath,
                ]);
            }
        }

        return redirect()->route('products.index')->with('msg', 'Produk berhasil disimpan.');
    }

    public function show($id)
    {
        $product = Product::with(['subCategory', 'brand', 'subVariant', 'images'])->findOrFail($id);

        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with('subVariant', 'images')->findOrFail($id);
        $subcategories = SubCategoryProduct::all();
        $brands = Brand::all();

        return view('products.edit', compact('product', 'subcategories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'markup' => 'required|numeric|min:0|max:100',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sub_category_product_id' => 'required|exists:sub_category_products,id',
            'brand_id' => 'required|exists:brands,id',
            'variants' => 'required|array',
            'variants.*.color' => 'required|string',
            'variants.*.size' => 'required|string',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        $markupPercentage = $request->markup;
        $markupAmount = ($request->price * $markupPercentage) / 100;
        $oldPrice = $request->price + $markupAmount;

        $ppnAmount = ($oldPrice * 11) / 100;
        $finalPrice = $oldPrice + $ppnAmount;

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'markup' => $markupPercentage,
            'old_price' => $oldPrice,
            'price' => $finalPrice,
            'sub_category_product_id' => $request->sub_category_product_id,
            'brand_id' => $request->brand_id,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('product_images', 'public');
                $product->images()->create([
                    'image_path' => $imagePath,
                ]);
            }
        }

        if ($request->has('deleted_images')) {
            $deletedImages = $request->deleted_images;
            foreach ($deletedImages as $imageId) {
                $image = $product->images()->findOrFail($imageId);
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        return redirect()->route('products.index')->with('msg', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $product->delete();

        return redirect()->route('products.index')->with('msg', 'Produk berhasil dihapus.');
    }
}
