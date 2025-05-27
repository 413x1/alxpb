<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $product = Product::with(['banners'])->where('is_active', true)->first();
        return view('pages.order-form', [
            'product' => $product
        ]);
    }
}
