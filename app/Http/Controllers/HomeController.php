<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $importantCategories = ['Shoes', 'Clothes'];

        $categories = CategoryProduct::with('brands')
            ->whereIn('name', $importantCategories)
            ->get();

        return view('home_user.home', compact('categories'));
    }
}
