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
        $totalOrder = Order::count();
        $pendingOrder = Order::where('status', 'pending')->count();
        $completedOrder = Order::where('status', 'paid')->count();
        $completedAmount = Order::where('status', 'paid')->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('total_price');
        $devices = Device::withCount('orders')->get();

        $voucher = Voucher::count();
        $redeemedVoucher = Voucher::redeemed()->count();
        $availableVoucher = Voucher::available()->count();

        $user = User::count();
        $deviceCount = Device::count();

        return view('pages.admin.index.index',compact('totalOrder', 'pendingOrder', 'completedOrder', 'completedAmount', 'user', 'voucher', 'redeemedVoucher', 'availableVoucher', 'devices', 'deviceCount'));
    }
}
