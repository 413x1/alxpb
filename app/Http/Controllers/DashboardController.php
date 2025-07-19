<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Device;
use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;

class DashboardController extends Controller
{
    public function index()
    {
        $banner = Banner::count();
        $device = Device::count();
        $user = User::count();
        $order = Order::count();
        $voucher = Voucher::count();

        return view('pages.admin.index.index', compact('banner', 'device', 'user', 'order', 'voucher'));
    }
}
