<?php

namespace App\Helpers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartHelper
{
    /**
     *
     * @return array
     */
    public static function getUserCartIds()
    {
        return Auth::check()
        ? Cart::whereUserId(Auth::id())->pluck('product_id')->all()
        : [];
    }
}


