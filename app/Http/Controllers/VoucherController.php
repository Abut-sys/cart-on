<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all();
        return view('vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('vouchers.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:vouchers',
            'discount_value' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'Terms_and_Conditions' => 'nullable|string',
            'usage_limit' => 'required|integer|min:1',
        ]);

        // Get the current date
        $currentDate = Carbon::now()->toDateString();

        // Determine the status based on the dates
        $status = ($currentDate >= $validatedData['start_date'] && $currentDate <= $validatedData['end_date']) ? 'active' : 'inactive';

        // Create the voucher
        Voucher::create([
            'code' => $validatedData['code'],
            'discount_value' => $validatedData['discount_value'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'Terms_and_Conditions' => $validatedData['Terms_and_Conditions'],
            'usage_limit' => $validatedData['usage_limit'],
            'status' => $status,
        ]);
        return redirect()->route('vouchers.index')->with('success', 'Voucher created successfully!');
    }

    public function edit(Voucher $voucher)
    {
        return view('vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validateData = $request->validate([
            'code' => 'required|string|max:255|unique:vouchers,code,' . $voucher->id, // Allow current code
            'discount_value' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'Terms_and_Conditions' => 'nullable|string',
            'usage_limit' => 'required|integer|min:1',
        ]);

        // Get the current date
        $currentDate = Carbon::now()->toDateString();

        // Determine the status based on the dates
        $status = ($currentDate >= $validateData['start_date'] && $currentDate <= $validateData['end_date']) ? 'active' : 'inactive';

        // Update the voucher
        $voucher->update(array_merge($validateData, ['status' => $status]));

        return redirect()->route('vouchers.index')->with('success', 'Voucher updated successfully!');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('vouchers.index')->with('success', 'Voucher deleted successfully!');
    }
}
