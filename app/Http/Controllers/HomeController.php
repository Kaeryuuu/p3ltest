<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = [
            (object) ['name' => 'Fashion', 'image' => 'images/fashion.jpg'],
            (object) ['name' => 'Elektronik', 'image' => 'images/electronics.jpg'],
            
        ];

        $products = [
            (object) ['name' => 'Produk 1', 'price' => 100000, 'image' => 'images/product1.jpg'],
            
        ];

        return view('homepage', compact('categories', 'products'));
    }
}

