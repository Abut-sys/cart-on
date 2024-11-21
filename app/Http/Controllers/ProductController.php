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
        $products = Product::with(['subVariants', 'brand', 'categoryProduct', 'subCategoryProducts'])->paginate(5); // Load sub-variants and brand, and paginate results
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



    //  public function store(Request $request)
    //  {
    //      // Validate request data
    //      $validatedData = $request->validate([
    //          'name' => 'required|string|max:255',
    //          'price' => 'required|numeric|min:0',
    //          'stock' => 'required|integer|min:0',
    //          'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //          'description' => 'nullable|string',
    //          'brands_id' => 'nullable|exists:brands,id',
    //          'sub_variants' => 'nullable|array',
    //          'sub_variants.*' => 'string|max:255',
    //          'category_products_id' => 'nullable|exists:category_products,id',
    //          'sub_category_products_id' => 'nullable|array|min:1',
    //          'sub_category_products_id.*' => 'exists:sub_category_products,id',
    //      ]);
    //     //  dd($request->all());

    //      // Process image upload if present
    //      if ($request->hasFile('image')) {
    //          $validatedData['image_path'] = $request->file('image')->store('product_images', 'public');
    //      }

    //      // Create the product without sub-variants and sub-categories
    //      $product = Product::create($validatedData);

    //      // Handle sub-variants if provided
    //      if (!empty($request->sub_variants)) {
    //          $subVariantsData = array_map(function ($name) {
    //              return ['name' => trim($name)];
    //          }, array_filter($request->sub_variants));

    //          // Bulk insert new sub-variants with product association
    //          $product->subVariants()->createMany($subVariantsData);
    //      }

    //      // Attach selected sub-categories
    //      if (!empty($request->sub_category_products_id)) {
    //          $product->subCategoryProduct()->sync($request->sub_category_products_id);
    //      }

    //      return redirect()->route('products.index')->with('success', 'Product Created Successfully.');
    //  }

     public function store(Request $request)
{
    // Validate the incoming request
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'nullable|string',
        'brands_id' => 'nullable|exists:brands,id',
        'sub_variants' => 'nullable|array',
        'sub_variants.*' => 'string|max:255',
        'category_products_id' => 'nullable|exists:category_products,id',
        'sub_category_products_id' => 'nullable|array|min:1',
        'sub_category_products_id.*' => 'exists:sub_category_products,id',
    ]);
    dd($request->all());
    // if ($request->fails()) {
    //     dd($request->all(), $request->errors());
    // }

    // dd($validatedData);

    // Process image upload, if present
    if ($request->hasFile('image')) {
        $validatedData['image_path'] = $request->file('image')->store('product_images', 'public');
    }

    // Create the product
    $product = Product::create($validatedData);

    // Add sub-variants if provided
    if (!empty($request->sub_variants)) {
        $subVariants = collect($request->sub_variants)
            ->filter() // Remove empty strings
            ->map(fn($name) => ['name' => trim($name)]) // Prepare data
            ->toArray();

        $product->subVariants()->createMany($subVariants);
    }

    // Attach sub-categories if provided
    // if (!empty($request->sub_category_products_id)) {
    //     $product->subCategoryProduct()->sync($request->sub_category_products_id);
    // }
    if ($request->filled('sub_category_products_id')) {
        $product->subCategoryProduct()->sync($request->input('sub_category_products_id'));
    }


    // Redirect to product listing with success message
    return redirect()->route('products.index')->with('success', 'Product Created Successfully.');
}



    public function show(Product $product, )
    {
        // Load all related data at once
        $product->load(['brand', 'subVariants', 'categoryProduct', 'subCategoryProducts']);
        return view('products.show', compact('product'));
    }


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


    // // Validate request data
    // $request->validate([
    //     'name' => 'required|string|max:255',
    //     'price' => 'required|numeric|min:0',
    //     'stock' => 'required|integer|min:0',
    //     'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image validation
    //     'description' => 'nullable|string',
    //     'brands_id' => 'nullable|exists:brands,id', // Validate brand ID
    //     'sub_variants' => 'nullable|array', // Change to array for better handling
    //     'sub_variants.*' => 'string|max:255', // Validate each sub-variant
    //     'category_products_id' => 'nullable|exists:category_products,id', // Validate brand ID
    //     'sub_category_products_id' => 'nullable|exists:sub_category_products,id', // Validate brand ID

    // ]);

    // // Prepare data for update
    // $data = $request->except('_token', 'image', 'sub_variants');

    // // Handle image upload if exists
    // if ($request->hasFile('image')) {
    //     if ($product->image_path) {
    //         Storage::disk('public')->delete($product->image_path); // Delete old image
    //     }
    //     $data['image_path'] = $request->file('image')->store('product_images', 'public'); // Store new image
    // }

    // // Update the product
    // $product->update($data);

    // // Update sub-variants if provided
    // if (!empty($request->sub_variants)) {
    //     // Clear existing sub-variants
    //     $product->subVariants()->delete();

    //     // Prepare new sub-variants for bulk insertion
    //     $subVariantsData = array_map(function ($name) {
    //         return ['name' => trim($name)]; // Prepare sub-variant data
    //     }, $request->sub_variants);

    //     $product->subVariants()->createMany($subVariantsData); // Bulk create new sub-variants
    // }

    // return redirect()->route('products.index')->with('success', 'Product Updated Successfully.');
    // }

//     public function update(Request $request, Product $product)
// {
//     // Validate request data
//     $validatedData = $request->validate([
//         'name' => 'required|string|max:255',
//         'price' => 'required|numeric|min:0',
//         'stock' => 'required|integer|min:0',
//         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
//         'description' => 'nullable|string',
//         'brands_id' => 'nullable|exists:brands,id',
//         'sub_variants' => 'nullable|array',
//         'sub_variants.*' => 'string|max:255',
//         'category_products_id' => 'nullable|exists:category_products,id',
//         'sub_category_products_id' => 'nullable|array|min:1',
//         'sub_category_products_id.*' => 'exists:sub_category_products,id',
//     ]);

//     // Handle image upload if exists
//     if ($request->hasFile('image')) {
//         $validatedData['image_path'] = $request->file('image')->store('product_images', 'public');
//         // Optionally, delete the old image if applicable
//         if ($product->image_path) {
//             Storage::disk('public')->delete($product->image_path);
//         }
//     }

//     // Update product without sub-variants and sub-categories
//     $product->update($validatedData);

//     // Update sub-variants
//     if (!empty($request->sub_variants)) {
//         // Remove existing sub-variants and create new ones
//         $product->subVariants()->delete();
//         $subVariantsData = array_map(function ($name) {
//             return ['name' => trim($name)];
//         }, array_filter($request->sub_variants));
//         $product->subVariants()->createMany($subVariantsData);
//     }

//     // Sync sub-categories
//     if (!empty($request->sub_category_products_id)) {
//         $product->subCategories()->sync($request->sub_category_products_id);
//     }

//     return redirect()->route('products.index')->with('success', 'Product Updated Successfully.');
// }


public function update(Request $request, Product $product)
{
    // Validate the incoming request
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'nullable|string',
        'brands_id' => 'nullable|exists:brands,id',
        'sub_variants' => 'nullable|array',
        'sub_variants.*' => 'string|max:255',
        'category_products_id' => 'nullable|exists:category_products,id',
        'sub_category_products_id' => 'nullable|array|min:1',
        'sub_category_products_id.*' => 'exists:sub_category_products,id',
    ]);

    // Handle image upload if provided
    if ($request->hasFile('image')) {
        $validatedData['image_path'] = $request->file('image')->store('product_images', 'public');

        // Delete the old image, if applicable
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
    }

    // Update the product
    $product->update($validatedData);

    // Update sub-variants
    if (!empty($request->sub_variants)) {
        $product->subVariants()->delete(); // Remove existing sub-variants

        $subVariants = collect($request->sub_variants)
            ->filter() // Remove empty strings
            ->map(fn($name) => ['name' => trim($name)]) // Prepare data
            ->toArray();

        $product->subVariants()->createMany($subVariants); // Add new sub-variants
    }

    // Sync sub-categories
    $product->subCategoryProduct()->sync($request->sub_category_products_id ?? []);

    // Redirect back with a success message
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
