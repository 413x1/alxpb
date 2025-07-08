<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class DashboardProductController extends Controller
{
    public function __construct()
    {
        $this->base_view_path = 'pages.admin.product.';
    }

    public function index()
    {
        $product = Product::with(['banners'])->where('is_active', true)->orderBy('id')->first();

        return view($this->base_view_path .'index',[
            'product' => $product
        ]);
    }
}
