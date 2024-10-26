<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\SubCategoryProduct;
use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    public function index()
    {
        $categories = CategoryProduct::with('subCategories')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sub_category_name' => 'nullable|string|max:255',
        ]);
        // Create the category
        $category = CategoryProduct::create(['name' => $request->name]);

        // If a subcategory name is provided, create the subcategory
        if ($request->sub_category_name) {
            $category->subCategories()->create(['name' => $request->sub_category_name]);
        }
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $category = CategoryProduct::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'subcategories' => 'array',
            'new_subcategories' => 'array',
            'deleted_subcategories' => 'array',
        ]);

        // Find the category by ID and update its name
        $category = CategoryProduct::findOrFail($id);
        $category->name = $request->input('name');
        $category->save();

        // Handle the deletion of subcategories
        if ($request->has('deleted_subcategories')) {
            foreach ($request->input('deleted_subcategories') as $subId) {
                $subCategory = SubCategoryProduct::find($subId);
                if ($subCategory) {
                    $subCategory->delete();
                }
            }
        }

        // Update existing subcategories
        if ($request->has('subcategories')) {
            foreach ($request->input('subcategories') as $subId => $subName) {
                $subCategory = SubCategoryProduct::find($subId);
                if ($subCategory) {
                    $subCategory->name = $subName;
                    $subCategory->save();
                }
            }
        }

        // Add new subcategories if any are provided
        if ($request->has('new_subcategories')) {
            foreach ($request->input('new_subcategories') as $newSubName) {
                SubCategoryProduct::create([
                    'name' => $newSubName,
                    'category_product_id' => $category->id,
                ]);
            }
        }

        // Redirect back to the index page with a success message
        return redirect()->route('categories.index')->with('success', 'Kategori dan sub kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = CategoryProduct::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
