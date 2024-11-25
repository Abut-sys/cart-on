<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\SubCategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['subCategory', 'brand', 'subVariant'])->paginate(10);
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
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sub_category_product_id' => 'required|exists:sub_category_products,id',
            'brand_id' => 'required|exists:brands,id',
            'variants' => 'required|array',
            'variants.*.color' => 'required|string',
            'variants.*.size' => 'required|string',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        $imagePath = $request->file('image_path')->store('product_images', 'public');

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => $imagePath,
            'sub_category_product_id' => $request->sub_category_product_id,
            'brand_id' => $request->brand_id,
        ]);

        foreach ($request->variants as $variant) {
            $product->subVariant()->create($variant);
        }

        return redirect()->route('products.index')->with('msg', 'Produk berhasil disimpan.');
    }

    public function show($id)
    {
        $product = Product::with(['subCategory', 'brand', 'subVariant'])->findOrFail($id);

        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with('subVariant')->findOrFail($id);
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
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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

        if ($request->hasFile('image_path')) {
            Storage::disk('public')->delete($product->image_path);
            $imagePath = $request->file('image_path')->store('product_images', 'public');
            $product->update(['image_path' => $imagePath]);
        }

        $product->subVariant()->delete();
        foreach ($request->variants as $variant) {
            $product->subVariant()->create($variant);
        }

        return redirect()->route('products.index')->with('msg', 'Produk berhasil diupdate.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        Storage::disk('public')->delete($product->image_path);

        $product->delete();

        return redirect()->route('products.index')->with('msg', 'Produk berhasil dihapus.');
    }
}
