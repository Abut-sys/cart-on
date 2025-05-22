<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Voucher;
use App\Models\ClaimVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\VoucherStatusChanged;
use App\Notifications\VoucherNotification;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = Voucher::query();

        // Filter berdasarkan kode voucher
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pagination
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
        $claimedVoucherIds = ClaimVoucher::where('user_id', Auth::id())->pluck('voucher_id')->toArray();

        $vouchers = Voucher::where('status', 'active')
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->whereNotIn('id', $claimedVoucherIds)
            ->whereColumn('used_count', '<', 'usage_limit')
            ->get();

        return view('vouchers.claim', compact('vouchers'));
    }

    public function claimedVouchers()
    {
        $claimedVouchers = ClaimVoucher::where('user_id', Auth::id())
            ->whereHas('voucher', function ($query) {
                $query->where('status', 'active')
                    ->where('start_date', '<=', Carbon::now())
                    ->where('end_date', '>=', Carbon::now());
            })
            ->get();

        return view('vouchers.claimed', compact('claimedVouchers'));
    }

    public function claimVoucher(Request $request, $voucherId)
    {
        $user = auth()->user();
        $voucher = Voucher::findOrFail($voucherId);

        if ($voucher->status !== 'active') {
            return back()->withErrors(['msg' => 'Voucher is not active.']);
        }

        if ($voucher->used_count >= $voucher->usage_limit) {
            return back()->withErrors(['msg' => 'Voucher usage limit reached.']);
        }

        $alreadyClaimed = ClaimVoucher::where('user_id', $user->id)
            ->where('voucher_id', $voucherId)
            ->exists();

        if ($alreadyClaimed) {
            return back()->withErrors(['msg' => 'You have already claimed this voucher.']);
        }

        ClaimVoucher::create([
            'user_id' => $user->id,
            'voucher_id' => $voucher->id,
        ]);

        $voucher->increment('used_count');

        $user->notify(new VoucherNotification(
            "You have successfully claimed a voucher {$voucher->code} 🎉",
            route('your-vouchers')
        ));

        return redirect()->route('voucher.claim')->with('msg', 'Voucher claimed successfully!');
    }
}
