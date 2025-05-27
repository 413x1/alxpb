<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index(Request $request): Factory|Application|View
    {
        $banners = Banner::where('is_active', true)->orderBy('updated_at', 'desc')->get(); 
        return view('pages.index', [
            'banners' => $banners
        ]);
    }
}
