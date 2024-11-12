<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        // Mengambil semua voucher
        $vouchers = Voucher::all();

        // Mengonversi start_date dan end_date menjadi objek Carbon jika belum
        foreach ($vouchers as $voucher) {
            $voucher->start_date = Carbon::parse($voucher->start_date);
            $voucher->end_date = Carbon::parse($voucher->end_date);
        }

        return view('vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('vouchers.create');
    }

    public function store(Request $request)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:vouchers',
            'discount_value' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'terms_and_conditions' => 'nullable|string',
            'usage_limit' => 'required|integer|min:1',
        ]);

        // Buat voucher baru
        $voucher = Voucher::create($validatedData);

        // Update status voucher sesuai dengan tanggal
        $voucher->updateStatus(); // Pastikan ada method updateStatus di model

        return redirect()->route('vouchers.index')->with('success', 'Voucher created successfully!');
    }

    public function edit(Voucher $voucher)
    {
        return view('vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:vouchers,code,' . $voucher->id,
            'discount_value' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'terms_and_conditions' => 'nullable|string',
            'usage_limit' => 'required|integer|min:1',
        ]);

        // Update voucher
        $voucher->update($validatedData);

        // Update status voucher sesuai dengan tanggal
        $voucher->updateStatus(); // Pastikan ada method updateStatus di model

        return redirect()->route('vouchers.index')->with('success', 'Voucher updated successfully!');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('vouchers.index')->with('success', 'Voucher deleted successfully!');
    }
}
