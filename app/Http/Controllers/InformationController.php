<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'image|nullable|max:2048', // Validasi untuk gambar
            'email' => 'required|email|unique:informations',
            'website_name' => 'required',
            'phone_number' => 'required',
            'company_address' => 'required',
            'about_us' => 'required',
        ]);

        // Menyimpan gambar
        $imagePath = $request->file('image') ? $request->file('image')->store('images') : null;

        Information::create([
            'image' => $imagePath,
            'email' => $request->email,
            'website_name' => $request->website_name,
            'phone_number' => $request->phone_number,
            'company_address' => $request->company_address,
            'about_us' => $request->about_us,
        ]);

        return redirect()->back()->with('success', 'Informasi berhasil disimpan.');
    }

    public function index()
    {
        $informations = Information::all();
        return view('information.index', compact('informations'));
    }
}
