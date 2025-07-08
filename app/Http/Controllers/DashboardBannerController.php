<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class DashboardBannerController extends Controller
{
    public function __construct()
    {
        $this->base_view_path = 'pages.admin.banner.';
    }

    public function index()
    {
        $banners = Banner::all();
        return view($this->base_view_path .'index',
        [
            'banners' => $banners
        ]);
    }
}
