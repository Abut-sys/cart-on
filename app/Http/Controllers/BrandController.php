<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchable = ['id', 'name', 'category'];
        
        $query = Brand::with('categoryProduct');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id', $request->search)
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhereHas('categoryProduct', function ($cat) use ($request) {
                        $cat->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $sortColumn = $request->input('sort_column', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        if (in_array($sortColumn, ['id', 'name']) && in_array($sortDirection, ['asc', 'desc'])) {
            $query->orderBy($sortColumn, $sortDirection);
        } elseif ($sortColumn === 'category' && in_array($sortDirection, ['asc', 'desc'])) {
            $query->join('category_products', 'brands.category_product_id', '=', 'category_products.id')
                ->orderBy('category_products.name', $sortDirection)
                ->select('brands.*');
        }

        $brands = $query->paginate(5)->withQueryString();

        return view('brands.index', compact('brands'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = CategoryProduct::all(); // Mendapatkan semua kategori produk
        return view('brands.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_product_id' => 'required|exists:category_products,id',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'category_product_id', 'description']);

        // Simpan file gambar jika ada
        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        Brand::create($data);

        return redirect()->route('brands.index')->with('success', 'Brand created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return view('brands.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        $categories = CategoryProduct::all(); // Mendapatkan semua kategori produk
        return view('brands.edit', compact('brand', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_product_id' => 'required|exists:category_products,id',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'category_product_id', 'description']);

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($brand->logo_path) {
                Storage::disk('public')->delete($brand->logo_path);
            }

            // Simpan file baru
            $data['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $brand->update($data);

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        if ($brand->logo_path) {
            Storage::disk('public')->delete($brand->logo_path);
        }

        $brand->delete();

        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully.');
    }
}
