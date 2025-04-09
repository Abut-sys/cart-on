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
        // Ambil nilai dari query string
        $search = $request->input('search');
        $sortId = $request->input('sort_id');
        $sortName = $request->input('sort_name');

        // Query dasar dengan relasi
        $query = Product::with(['subCategory', 'brand', 'subVariant', 'images']);

        // Filter pencarian berdasarkan ID atau nama produk
        if ($search) {
            $query->where('id', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%");
        }

        // Filter pengurutan berdasarkan ID
        if ($sortId) {
            $query->orderBy('id', $sortId);
        }

        // Filter pengurutan berdasarkan nama
        if ($sortName) {
            $query->orderBy('name', $sortName);
        }

        // Ambil hasil dengan pagination
        $products = $query->paginate(10);

        // Kirim data ke view
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

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sub_category_product_id' => 'required|exists:sub_category_products,id',
            'brand_id' => 'required|exists:brands,id',
            'variants' => 'required|array',
            'variants.*.color' => 'required|string',
            'variants.*.size' => 'required|string',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
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
