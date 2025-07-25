<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Helpers\WishlistHelper;
use App\Models\Brand;
use App\Models\Product;
use App\Models\SubCategoryProduct;
use App\Models\SubVariant;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class ProductAllController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil warna, ukuran, brand, dan kategori untuk filter
        $color = SubVariant::distinct()->pluck('color');
        $size = SubVariant::distinct()->pluck('size');
        $brand = Brand::pluck('name');
        $category = SubCategoryProduct::pluck('name');  // Mengambil semua kategori produk

        $userCartIds = CartHelper::getUserCartIds();
        $userWishlistIds = WishlistHelper::getUserWishlistIds();


        $query = Product::query();

        if ($request->has('search') && $request->input('search') !== '') {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        // Menangani filter berdasarkan warna (color)
        if ($request->filled('color')) {
            $query->whereHas('subVariant', function ($q) use ($request) {
                $q->whereIn('color', (array) $request->color);
            });
        }

        // Menangani filter berdasarkan ukuran (size)
        if ($request->filled('size')) {
            $query->whereHas('subVariant', function ($q) use ($request) {
                $q->whereIn('size', (array) $request->size);
            });
        }

        // Menangani filter berdasarkan brand
        if ($request->filled('brand')) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->whereIn('name', (array) $request->brand);
            });
        }

        // Menangani filter berdasarkan kategori (category)
        if ($request->filled('category')) {
            $query->whereHas('subCategory', function ($q) use ($request) {
                $q->whereIn('name', (array) $request->category);
            });
        }

        // Menangani pengurutan produk berdasarkan sort
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'bestselling':
                    $query->orderBy('sales', 'desc');
                    break;
                case 'lowest_price':
                    $query->orderBy('price', 'asc');
                    break;
                case 'highest_price':
                    $query->orderBy('price', 'desc');
                    break;
            }
        }

        // Menampilkan produk yang sudah difilter dan diurutkan
        $products = $query->paginate(10);

        return view('products.all', compact('products', 'color', 'size', 'category', 'brand', 'userCartIds','userWishlistIds'));
    }



    public function show($id)
    {
    $userCartIds = CartHelper::getUserCartIds(); // Ensure this returns an array
    $userWishlistIds = WishlistHelper::getUserWishlistIds();

    $product = Product::with(['subCategory', 'brand', 'subVariant', 'images'])->findOrFail($id);

    return view('products.show-user', compact('product', 'userCartIds', 'userWishlistIds'));
    }

    public function getStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'color' => 'required|string',
            'size' => 'required|string',
        ]);

        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $stock = $product->subVariant()
            ->where('color', $request->color)
            ->where('size', $request->size)
            ->first()->stock ?? 0;

        return response()->json(['stock' => $stock]);
    }
}
