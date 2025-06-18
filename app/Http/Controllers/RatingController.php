<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'information_id' => 'required|exists:information,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $informationId = $request->information_id;

        // Cek apakah user sudah memberi review
        $existingReview = Review::where('user_id', $user->id)
            ->where('reviewable_id', $informationId)
            ->where('reviewable_type', Information::class)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('msg', 'Anda sudah memberikan ulasan.');
        }

        // Simpan review
        Review::create([
            'user_id' => $user->id,
            'reviewable_id' => $informationId,
            'reviewable_type' => Information::class,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Hitung ulang rata-rata dan jumlah review
        $info = Information::find($informationId);
        $avg = round($info->reviews()->avg('rating'), 1);
        $count = $info->reviews()->count();

        $info->update([
            'rating' => $avg,
            'rating_count' => $count,
        ]);

        // Hapus session login_time agar modal tidak muncul lagi
        session()->forget('login_time');

        return redirect()->back()->with('msg', 'Terima kasih atas ulasan Anda!');
    }

    public function check(Request $request)
    {
        $hasRated = false;

        if (Auth::check()) {
            $hasRated = Review::where('user_id', Auth::id())
                ->where('reviewable_id', $request->information_id)
                ->where('reviewable_type', Information::class)
                ->exists();
        }

        return response()->json(['hasRated' => $hasRated]);
    }
}
