<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Helpers\WishlistHelper;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\Chat; // Add this import
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $importantCategories = ['Shoes', 'Clothes'];

        $categories = CategoryProduct::with('brands')->get();

        $userCartIds = CartHelper::getUserCartIds();
        $userWishlistIds = WishlistHelper::getUserWishlistIds();

        // Fetch chat messages for authenticated users
        $messages = auth()->check()
            ? Chat::where('user_id', auth()->id())->get()
            : collect(); // Empty collection for guests

        // Get all products
        $products = Product::all();

        return view('home_user.home', compact(
            'categories',
            'products',
            'userCartIds',
            'userWishlistIds',
            'messages' // Include messages in the view data
        ));
    }
}
