<?php

namespace App\Http\Controllers;

use App\Models\product;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = product::all();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all();
        return view('products.create', compact ('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
            'description' => 'nullable',
            'brands_id' => 'nullable|exists:brands,id' // Corrected validation rule
        ]);

        $data = $request->except('_token', 'image');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('product_images', 'public');
        }

        Product::create($data); // Removed named argument syntax for compatibility
        return redirect()->route('products.index')->with('success', 'Product Created Successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product, )
    {
        $product = $product->load('brand'); // load the related brand
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $brands = Brand::all();
        return view('products.edit', compact('product', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Image validation
            'description' => 'nullable',
            'brands_id' => 'nullable|exists:brands,id', // Corrected validation rule
        ]);

        

        $data = $request->except('_token', 'image');

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path); // Delete old image
            }
            $data['image_path'] = $request->file('image')->store('product_images', 'public'); // Store new image
        }

        $product->update($data); // Update product with validated data
        return redirect()->route('products.index')->with('success', 'Product Updated Successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product Deleted Successfully.');
    }

}
