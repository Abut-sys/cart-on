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
        return Auth::check()
        ? Wishlist::whereUserId(Auth::id())->pluck('product_id')->all()
        : [];
    }
}
