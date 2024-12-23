<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Voucher;
use App\Models\UserVoucher;
use Illuminate\Http\Request;
use App\Events\VoucherStatusChanged;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = Voucher::query();

        // Filter berdasarkan code (mendukung pencarian parsial)
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        // Filter berdasarkan status (active atau inactive)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Paginate hasil pencarian
        $vouchers = $query->paginate(10);

        return view('vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('vouchers.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:vouchers',
            'discount_value' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'terms_and_conditions' => 'nullable|string',
            'usage_limit' => 'required|integer|min:1',
        ]);

        $voucher = Voucher::create($validatedData);

        $voucher->updateStatus();

        // Trigger event untuk notifikasi
        event(new VoucherStatusChanged($voucher));

        return redirect()->route('vouchers.index')->with('success', 'Voucher created successfully!');
    }

    public function edit(Voucher $voucher)
    {
        return view('vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:vouchers,code,' . $voucher->id,
            'discount_value' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'terms_and_conditions' => 'nullable|string',
            'usage_limit' => 'required|integer|min:1',
        ]);

        $voucher->update($validatedData);

        $voucher->updateStatus();

        return redirect()->route('vouchers.index')->with('success', 'Voucher updated successfully!');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('vouchers.index')->with('success', 'Voucher deleted successfully!');
    }

    public function claim()
    {
        // Retrieve only active vouchers that can be claimed (status = 'active' and within the date range)
        $claimedVoucherIds = UserVoucher::where('user_id', Auth::id())->pluck('voucher_id')->toArray();

        $vouchers = Voucher::where('status', 'active')
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->whereNotIn('id', $claimedVoucherIds) // Exclude claimed vouchers
            ->get();

        // Return the claim page with the available vouchers
        return view('vouchers.claim', compact('vouchers'));
    }

    public function claimedVouchers()
    {
        // Ambil voucher yang sudah diklaim oleh pengguna yang sedang login
        $claimedVouchers = UserVoucher::where('user_id', Auth::id())->with('voucher')->get();

        return view('vouchers.claimed', compact('claimedVouchers'));
    }

    public function claimVoucher($voucherId)
    {
        $voucher = Voucher::findOrFail($voucherId);

        // Pastikan voucher masih valid (jika perlu)
        if ($voucher->start_date <= now() && $voucher->end_date >= now()) {
            // Simpan klaim voucher
            UserVoucher::create([
                'voucher_id' => $voucher->id,
                'user_id' => auth()->id(), // ID pengguna yang mengklaim
            ]);

            return redirect()->back()->with('msg', 'Voucher berhasil diklaim!');
        }

        return redirect()->back()->with('msg', 'Voucher tidak valid atau sudah expired.');
    }
}
