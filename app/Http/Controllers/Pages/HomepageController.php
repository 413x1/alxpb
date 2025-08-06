<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index(Request $request): Factory|Application|View
    {
        $product = Product::with(['banners'])->where('is_active', true)->first();
        return view('pages.theme1.choose-strip', [
            'product' => $product,
        ]);
    }
}
