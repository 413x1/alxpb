<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DashboardDeviceController extends Controller
{
    private string $base_view_path;

    public function __construct()
    {
        $this->base_view_path = 'pages.admin.device.';
    }

    public function index()
    {
        $devices = Device::latest()->paginate(10);

        return view($this->base_view_path.'index', compact('devices'));
    }

    public function create()
    {
        return view('pages.admin.device.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'identifier' => 'required|string|max:255|unique:devices,identifier',
            'code' => 'required|string|max:255|unique:devices,code',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Device::create($validated);

        return redirect()->route('dashboard.devices.index')
            ->with('success', 'Device created successfully.');
    }

    public function edit(Device $device)
    {
        return view('pages.admin.device.edit', compact('device'));
    }

    public function update(Request $request, Device $device)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'identifier' => 'required|string|max:255|unique:devices,identifier,'.$device->id,
            'code' => 'required|string|max:255|unique:devices,code,'.$device->id,
            'is_active' => 'boolean',
        ]);

        $device->update($validated);

        return redirect()->route('dashboard.devices.index')
            ->with('success', 'Device updated successfully.');
    }

    public function destroy(Device $device)
    {
        $device->delete();

        return redirect()->route('dashboard.devices.index')
            ->with('success', 'Device deleted successfully.');
    }
}
