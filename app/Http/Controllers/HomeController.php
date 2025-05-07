<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Helpers\WishlistHelper;
use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $importantCategories = ['Shoes', 'Clothes'];

        $categories = CategoryProduct::with('brands')->get();

            $userCartIds = CartHelper::getUserCartIds();
            $userWishlistIds = WishlistHelper::getUserWishlistIds();


        // Ambil semua produk dari database
        $products = Product::all();

        // Kirim data ke view
        return view('home_user.home', compact('categories','products', 'userCartIds', 'userWishlistIds'));
    }
}
