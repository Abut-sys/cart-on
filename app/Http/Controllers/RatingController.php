<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'information_id' => 'required|exists:information,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:500', // Sesuaikan dengan frontend limit
            ]);

            $user = Auth::user();
            $informationId = $request->information_id;

            // Cek apakah user sudah memberi review untuk information ini
            $existingReview = Review::where('user_id', $user->id)
                ->where('reviewable_id', $informationId)
                ->where('reviewable_type', Information::class)
                ->first();

            if ($existingReview) {
                return redirect()->back()->withErrors(['msg' => 'Anda sudah memberikan ulasan untuk informasi ini.']);
            }

            // Gunakan database transaction untuk consistency
            DB::transaction(function () use ($user, $informationId, $request) {
                // Simpan review
                Review::create([
                    'user_id' => $user->id,
                    'reviewable_id' => $informationId,
                    'reviewable_type' => Information::class,
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                ]);

                // Update rating information
                $this->updateInformationRating($informationId);
            });

            // Hapus session login_time agar modal tidak muncul lagi
            session()->forget('login_time');

            return redirect()->back()->with('msg', 'Terima kasih atas ulasan Anda!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error storing rating: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg' => 'Terjadi kesalahan saat menyimpan ulasan. Silakan coba lagi.']);
        }
    }

    public function check(Request $request)
    {
        try {
            $request->validate([
                'information_id' => 'required|exists:information,id',
            ]);

            $hasRated = false;

            if (Auth::check()) {
                $hasRated = Review::where('user_id', Auth::id())
                    ->where('reviewable_id', $request->information_id)
                    ->where('reviewable_type', Information::class)
                    ->exists();
            }

            return response()->json([
                'hasRated' => $hasRated,
                'isAuthenticated' => Auth::check()
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking rating: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan'], 500);
        }
    }

    private function updateInformationRating($informationId)
    {
        $info = Information::find($informationId);

        if (!$info) {
            throw new \Exception('Information not found');
        }

        // Hitung ulang rata-rata dan jumlah review
        $reviews = $info->reviews();
        $avg = $reviews->avg('rating');
        $count = $reviews->count();

        // Update dengan nilai yang sudah dibulatkan
        $info->update([
            'rating' => $avg ? round($avg, 1) : 0,
            'rating_count' => $count,
        ]);
    }

    public function updateRating($informationId)
    {
        try {
            $this->updateInformationRating($informationId);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error updating rating: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan'], 500);
        }
    }

    public function getRatingStats($informationId)
    {
        try {
            $info = Information::find($informationId);

            if (!$info) {
                return response()->json(['error' => 'Information not found'], 404);
            }

            $reviews = $info->reviews();

            // Hitung distribusi rating
            $ratingDistribution = [];
            for ($i = 1; $i <= 5; $i++) {
                $ratingDistribution[$i] = $reviews->where('rating', $i)->count();
            }

            return response()->json([
                'average' => $info->rating,
                'total' => $info->rating_count,
                'distribution' => $ratingDistribution
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting rating stats: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan'], 500);
        }
    }
}
