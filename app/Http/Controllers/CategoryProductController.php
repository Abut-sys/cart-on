<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\SubCategoryProduct;
use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    public function index(Request $request)
    {
        $query = CategoryProduct::with('subCategories');

        if ($request->search) {
            $query->where('id', $request->search)
                ->orWhere('name', 'like', '%' . $request->search . '%');
        }

        if ($request->sort_id) {
            $query->orderBy('id', $request->sort_id);
        }

        if ($request->sort_name) {
            $query->orderBy('name', $request->sort_name);
        }

        $categories = $query->paginate(5);

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
            'subcategories' => 'array', // Memastikan bahwa subcategories adalah array
            'subcategories.*' => 'nullable|string|max:255',
        ]);
        // Create the category
        $category = CategoryProduct::create(['name' => $request->name]);

        // If a subcategory name is provided, create the subcategory
        if ($request->has('sub_category_name')) {
            foreach ($request->input('sub_category_name') as $subCategoryName) {
                if (!empty($subCategoryName)) {
                    $category->subCategories()->create([
                        'name' => $subCategoryName,
                    ]);
                }
            }
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
