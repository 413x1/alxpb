<?php

namespace App\Http\Controllers;

use App\Helpers\General;
use App\Models\Device;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class DeviceAuthenticateController extends Controller
{
    public function index(Request $request): Factory|Application|View
    {
        return view('pages.theme1.index');
    }

    public function authtenticate(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $device = Device::where('code', $request->code)
            ->where('is_active', true)
            ->first();

        if (!$device) {
            return back()->withErrors(['code' => 'Invalid device code']);
        }

        // Generate identifier if not exists
        if (empty($device->identifier)) {
            $device->identifier = General::generateDeviceIdentifier();
            $device->save();
        }

        // Set session
        session([
            'active_device' => $device->identifier,
            'active_device_id' => $device->id,
            'active_device_name' => $device->name
        ]);

        return redirect()->intended('/');
    }
}
