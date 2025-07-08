<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DashboardDeviceController extends Controller
{
    public function __construct()
    {
        $this->base_view_path = 'pages.admin.device.';
    }

    public function index()
    {
        $devices = Device::all();
        return view($this->base_view_path .'index', [
            'devices' => $devices
        ]);
    }
}
