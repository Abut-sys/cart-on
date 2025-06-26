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
        $searchable = ['id', 'code', 'start_date', 'end_date', 'usage_limit'];

        $query = Voucher::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%$search%")
                    ->orWhere('type', 'like', "%$search%")
                    ->orWhere('terms_and_conditions', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('discount_value', 'like', "%$search%")
                    ->orWhere('usage_limit', 'like', "%$search%")
                    ->orWhereDate('start_date', $search)
                    ->orWhereDate('end_date', $search);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
            $searchable[] = 'discount_value';
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $sortColumn = $request->input('sort_column', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        if (in_array($sortColumn, $searchable) && in_array($sortDirection, ['asc', 'desc'])) {
            $query->orderBy($sortColumn, $sortDirection);
        }

        $vouchers = $query->paginate(10)->withQueryString();

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
            'type' => 'required|in:percentage,fixed',
            'discount_value' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type === 'percentage' && ($value < 0 || $value > 100)) {
                        $fail('Percentage discount must be between 0 and 100.');
                    } elseif ($request->type === 'fixed' && $value < 0) {
                        $fail('Fixed discount must be at least 0.');
                    }
                }
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'terms_and_conditions' => 'nullable|string',
            'usage_limit' => 'required|integer|min:1',
        ]);

        Voucher::create($validatedData);

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
            'type' => 'required|in:percentage,fixed',
            'discount_value' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type === 'percentage' && ($value < 0 || $value > 100)) {
                        $fail('Percentage discount must be between 0 and 100.');
                    } elseif ($request->type === 'fixed' && $value < 0) {
                        $fail('Fixed discount must be at least 0.');
                    }
                }
            ],
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
            ->whereNotIn('voucher_id', function ($query) {
                $query->select('voucher_id')
                    ->from('user_voucher')
                    ->where('user_id', Auth::id());
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
