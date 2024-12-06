<?php

namespace App\Helpers;

use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistHelper
{
    /**
     *
     * @return array
     */
    public static function getUserWishlistIds()
    {
        if (Auth::check()) {
            return Wishlist::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        }

        return [];
    }
}
