<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data dummy atau bisa diambil dari database
        $visitorsOnline = 2048;
        $productsSold = 1002;
        $productsArrivedSafely = 505;

        $recentProducts = [
            ['name' => 'Mercedes C63 AMG Black Series', 'number' => '9602400005', 'status' => 'Arrived', 'payment' => 'Paid'],
            ['name' => 'Lenovo Thinkplus GM2 Pro', 'number' => '908912128', 'status' => 'Pending', 'payment' => 'Due'],
            ['name' => 'SR20DETT', 'number' => 'I620070913', 'status' => 'Arrived', 'payment' => 'Paid'],
        ];

        $weeklySales = [700, 300, 500, 200, 600, 400, 800];
        $totalData = 906024;
        $weeklyStats = [
            'Minggu Pertama' => 826372,
            'Minggu Kedua' => 601928,
            'Minggu Ketiga' => 774124,
            'Minggu Keempat' => 660389,
            'Minggu Kelima' => 541852,
        ];

        return view('dashboard', compact(
            'visitorsOnline',
            'productsSold',
            'productsArrivedSafely',
            'recentProducts',
            'weeklySales',
            'totalData',
            'weeklyStats'
        ));
    }
}
