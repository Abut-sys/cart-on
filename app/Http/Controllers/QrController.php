<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QrController extends Controller
{
    public function scan()
    {
        return view('products.partials.scanQR'); // Your scanning view
    }
}
