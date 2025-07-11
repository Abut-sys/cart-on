<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Helpers\WishlistHelper;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Chat;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $importantCategories = ['Shoes', 'Clothes'];

        // Get categories with their brands (if needed for other sections)
        $categories = CategoryProduct::with('brands')->get();

        // Get all brands without trying to load non-existent 'category' relationship
        $brands = Brand::orderBy('name')->get();

        $userCartIds = CartHelper::getUserCartIds();
        $userWishlistIds = WishlistHelper::getUserWishlistIds();

        // Fetch chat messages for authenticated users
        $messages = auth()->check()
            ? Chat::where('from_user_id', auth()->id())
                ->orWhere('to_user_id', auth()->id())
                ->get()
            : collect();

        // Get all products
        $products = Product::all();

        return view('home_user.home',compact(
                'categories',
                'products',
                'userCartIds',
                'userWishlistIds',
                'messages',
                'brands'
            ),
        );
    }
}
