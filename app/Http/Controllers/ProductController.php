<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\SubCategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
                $query->leftJoin('sub_category_products', 'products.sub_category_product_id', '=', 'sub_category_products.id')->orderBy('sub_category_products.name', $sortDirection)->select('products.*');
            } elseif ($sortColumn === 'brand') {
                $query->leftJoin('brands', 'products.brand_id', '=', 'brands.id')->orderBy('brands.name', $sortDirection)->select('products.*');
            } elseif (in_array($sortColumn, $variantSearchable)) {
                $query
                    ->leftJoin('sub_variants', 'products.id', '=', 'sub_variants.product_id')
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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'markup' => 'nullable|numeric|min:0|max:100',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sub_category_product_id' => 'required|exists:sub_category_products,id',
            'brand_id' => 'required|exists:brands,id',
            'variants' => 'nullable|array',
            'variants.*.color' => 'nullable|string|max:50',
            'variants.*.size' => 'nullable|string|max:20',
            'variants.*.stock' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Calculate pricing - PPN first, then markup
            $basePrice = $validatedData['price'];

            // Step 1: Calculate PPN (11%)
            $ppnAmount = ($basePrice * 11) / 100;
            $priceWithPPN = $basePrice + $ppnAmount;

            // Step 2: Calculate markup on price that already includes PPN
            $markupPercentage = $validatedData['markup'] ?? 0;
            $markupAmount = ($priceWithPPN * $markupPercentage) / 100;
            $finalPrice = $priceWithPPN + $markupAmount;

            // Create product
            $product = Product::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'markup' => $markupPercentage,
                'old_price' => $basePrice, // Original price before PPN and markup
                'price' => $finalPrice,    // Final price after PPN and markup
                'sub_category_product_id' => $validatedData['sub_category_product_id'],
                'brand_id' => $validatedData['brand_id'],
            ]);

            // Create variants (only if provided and contain valid data)
            if (!empty($validatedData['variants'])) {
                $this->createVariants($product, $validatedData['variants']);
            }

            // Handle image uploads
            if ($request->hasFile('images')) {
                $this->handleImageUploads($request->file('images'), $product);
            }

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Produk berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan produk.']);
        }
    }

    public function show($id)
    {
        $product = Product::with(['subCategory', 'brand', 'subVariant', 'images'])->findOrFail($id);

        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with(['subVariant', 'images'])->findOrFail($id);
        $subcategories = SubCategoryProduct::select('id', 'name')->get();
        $brands = Brand::select('id', 'name')->get();

        return view('products.edit', compact('product', 'subcategories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'markup' => 'nullable|numeric|min:0|max:100',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sub_category_product_id' => 'required|exists:sub_category_products,id',
            'brand_id' => 'required|exists:brands,id',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:sub_variants,id',
            'variants.*.color' => 'nullable|string|max:50',
            'variants.*.size' => 'nullable|string|max:20',
            'variants.*.stock' => 'nullable|integer|min:0',
            'deleted_images' => 'nullable|array',
            'deleted_images.*' => 'exists:product_images,id',
        ]);

        try {
            DB::beginTransaction();

            // Calculate pricing - PPN first, then markup
            $basePrice = $validatedData['price'];

            // Step 1: Calculate PPN (11%)
            $ppnAmount = ($basePrice * 11) / 100;
            $priceWithPPN = $basePrice + $ppnAmount;

            // Step 2: Calculate markup on price that already includes PPN
            $markupPercentage = $validatedData['markup'] ?? 0;
            $markupAmount = ($priceWithPPN * $markupPercentage) / 100;
            $finalPrice = $priceWithPPN + $markupAmount;

            // Update product
            $product->update([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'markup' => $markupPercentage,
                'old_price' => $basePrice, // Original price before PPN and markup
                'price' => $finalPrice,    // Final price after PPN and markup
                'sub_category_product_id' => $validatedData['sub_category_product_id'],
                'brand_id' => $validatedData['brand_id'],
            ]);

            // Handle variants update/create/delete
            if (isset($validatedData['variants'])) {
                $this->updateVariants($product, $validatedData['variants']);
            } else {
                // If no variants provided, delete all existing variants
                $product->subVariant()->delete();
            }

            // Handle new image uploads
            if ($request->hasFile('images')) {
                $this->handleImageUploads($request->file('images'), $product);
            }

            // Handle image deletions
            if (!empty($validatedData['deleted_images'])) {
                $this->deleteImages($product, $validatedData['deleted_images']);
            }

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Product berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui produk.']);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            DB::beginTransaction();

            // Delete all product images from storage
            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }

            // Delete product (cascade will handle variants and images)
            $product->delete();

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus produk.']);
        }
    }

    /**
     * Handle image uploads for a product
     */
    private function handleImageUploads(array $images, Product $product): void
    {
        foreach ($images as $image) {
            $imagePath = $image->store('product_images', 'public');
            $product->images()->create([
                'image_path' => $imagePath,
            ]);
        }
    }

    /**
     * Create product variants (only if they contain meaningful data)
     */
    private function createVariants(Product $product, array $variants): void
    {
        foreach ($variants as $variant) {
            // Only create variant if at least one field has meaningful content
            if ($this->hasValidVariantData($variant)) {
                $product->subVariant()->create([
                    'color' => trim($variant['color'] ?? ''),
                    'size' => trim($variant['size'] ?? ''),
                    'stock' => $variant['stock'] ?? 0,
                ]);
            }
        }
    }

    /**
     * Update product variants (create new, update existing, delete removed)
     */
    private function updateVariants(Product $product, array $variants): void
    {
        $existingVariantIds = $product->subVariant()->pluck('id')->toArray();
        $submittedVariantIds = collect($variants)->filter(fn($variant) => isset($variant['id']))->pluck('id')->toArray();

        // Delete variants that are no longer in the submitted data
        $variantsToDelete = array_diff($existingVariantIds, $submittedVariantIds);
        if (!empty($variantsToDelete)) {
            $product->subVariant()->whereIn('id', $variantsToDelete)->delete();
        }

        // Update existing variants or create new ones
        foreach ($variants as $variantData) {
            // Only process if variant has meaningful data
            if ($this->hasValidVariantData($variantData)) {
                if (isset($variantData['id'])) {
                    // Update existing variant
                    $product
                        ->subVariant()
                        ->where('id', $variantData['id'])
                        ->update([
                            'color' => trim($variantData['color'] ?? ''),
                            'size' => trim($variantData['size'] ?? ''),
                            'stock' => $variantData['stock'] ?? 0,
                        ]);
                } else {
                    // Create new variant
                    $product->subVariant()->create([
                        'color' => trim($variantData['color'] ?? ''),
                        'size' => trim($variantData['size'] ?? ''),
                        'stock' => $variantData['stock'] ?? 0,
                    ]);
                }
            } elseif (isset($variantData['id'])) {
                // If existing variant has no meaningful data, delete it
                $product->subVariant()->where('id', $variantData['id'])->delete();
            }
        }
    }

    /**
     * Check if variant data contains meaningful information
     */
    private function hasValidVariantData(array $variant): bool
    {
        return !empty(trim($variant['color'] ?? '')) || !empty(trim($variant['size'] ?? '')) || (isset($variant['stock']) && $variant['stock'] > 0);
    }

    /**
     * Delete specified images
     */
    private function deleteImages(Product $product, array $imageIds): void
    {
        $images = $product->images()->whereIn('id', $imageIds)->get();

        foreach ($images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }
    }
}
