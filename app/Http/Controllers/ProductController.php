<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\product;
use App\Models\Brand;
use App\Models\SubCategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['subVariants', 'brand', 'categoryProduct', 'subCategoryProduct'])->paginate(10); // Load sub-variants and brand, and paginate results
        return view('products.index', compact('products'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all();
        $categoryproducts = CategoryProduct::all();
        $subcategoryproducts = SubCategoryProduct::all();
        return view('products.create', compact ('brands', 'categoryproducts', 'subcategoryproducts'));
    }


    public function store(Request $request)
    {
    // Validate request data
    $request->validate([
        'name' => 'required|string|max:255', // Ensure name is a string with max length
        'price' => 'required|numeric|min:0', // Ensure price is numeric and positive
        'stock' => 'required|integer|min:0', // Ensure stock is a non-negative integer
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Make image optional
        'description' => 'nullable|string', // Ensure description is a string
        'brands_id' => 'nullable|exists:brands,id', // Validate brand ID
        'sub_variants' => 'nullable|array', // Validate as an array
        'sub_variants.*' => 'string|max:255', // Validate each sub-variant
        'category_products_id' => 'nullable|exists:category_products,id', // Validate brand ID
        'sub_category_products_id' => 'nullable|exists:sub_category_products,id', // Validate brand ID

    ]);

    // Prepare data for product creation
    $data = $request->except('_token', 'image');

    // Handle image upload if exists
    if ($request->hasFile('image')) {
        $data['image_path'] = $request->file('image')->store('product_images', 'public');
    }

    // Create the product
    $product = Product::create($data);

    // Handle sub-variants if provided
    if (!empty($request->sub_variants)) {
        // Prepare new sub-variants for bulk insertion
        $subVariantsData = array_filter($request->sub_variants, function ($name) {
            return !empty(trim($name)); // Filter out empty sub-variant names
        });

        if (!empty($subVariantsData)) {
            // Map to the required format with product_id
            $subVariantsData = array_map(function ($name) use ($product) {
                return [
                    'name' => trim($name),
                    'product_id' => $product->id,
                ];
            }, $subVariantsData);

            // Bulk create new sub-variants
            $product->subVariants()->createMany($subVariantsData);
        }
    }

    return redirect()->route('products.index')->with('success', 'Product Created Successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product, )
    {
        // Load all related data at once
        $product->load(['brand', 'subVariants', 'categoryProduct', 'subCategoryProduct']);
        return view('products.show', compact('product'));
    }

    // {
    //     $product = $product->load('brand', 'subVariants', 'categoryProducts','subCategoryProducts' ); // load the related brand
    //     return view('products.show', compact('product'));
    // }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // Load necessary relationships for the product and get all brand/category data
        $product->load(['brand', 'subVariants', 'categoryProduct', 'subCategoryProduct']);
        $brands = Brand::all();
        $categoryProducts = CategoryProduct::all();
        $subCategoryProducts = SubCategoryProduct::all();

        return view('products.edit', compact('product', 'brands', 'categoryProducts', 'subCategoryProducts'));
    }
    // {
    //     $product->load('subVariants'); // Load existing sub-variants for the product
    //     $brands = Brand::all(); // Load all brands for the brand selection dropdown
    //     return view('products.edit', compact('product', 'brands', 'categoryProducts','subcategoryproducts'));
    // }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
    // Validate request data
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image validation
        'description' => 'nullable|string',
        'brands_id' => 'nullable|exists:brands,id', // Validate brand ID
        'sub_variants' => 'nullable|array', // Change to array for better handling
        'sub_variants.*' => 'string|max:255', // Validate each sub-variant
        'category_products_id' => 'nullable|exists:category_products,id', // Validate brand ID
        'sub_category_products_id' => 'nullable|exists:sub_category_products,id', // Validate brand ID

    ]);

    // Prepare data for update
    $data = $request->except('_token', 'image', 'sub_variants');

    // Handle image upload if exists
    if ($request->hasFile('image')) {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path); // Delete old image
        }
        $data['image_path'] = $request->file('image')->store('product_images', 'public'); // Store new image
    }

    // Update the product
    $product->update($data);

    // Update sub-variants if provided
    if (!empty($request->sub_variants)) {
        // Clear existing sub-variants
        $product->subVariants()->delete();

        // Prepare new sub-variants for bulk insertion
        $subVariantsData = array_map(function ($name) {
            return ['name' => trim($name)]; // Prepare sub-variant data
        }, $request->sub_variants);

        $product->subVariants()->createMany($subVariantsData); // Bulk create new sub-variants
    }

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
