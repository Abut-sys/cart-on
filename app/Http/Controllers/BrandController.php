<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\CategoryBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::paginate(5);
        return view('brands.index', compact('brands'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('brands.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
            'description'=> 'nullable',
        ]);

        $data = $request->except('_token');

        // Simpan file gambar
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo_path'] = $path;
        }

        Brand::create($data);
        return redirect()->route('brands.index')->with('success', 'Brand Created Successfully.');
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
        return view('brands.edit', compact('brand'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required',
            'logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
            'description'=> 'nullable',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($brand->logo_path) {
                Storage::disk('public')->delete($brand->logo_path);
            }

            // Simpan file baru
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo_path'] = $path;
        }

        $brand->update($data);
        return redirect()->route('brands.index')->with('success', 'Brand Updated Successfully.');
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
        return redirect()->route('brands.index')->with('success', 'Brand Deleted Successfully.');
    }
}
