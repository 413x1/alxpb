<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardBannerController extends Controller
{
    private string $base_view_path;

    public function __construct()
    {
        $this->base_view_path = 'pages.admin.banner.';
    }

    public function index()
    {
        $banners = Banner::latest()->paginate(10);

        return view($this->base_view_path.'index', compact('banners'));
    }

    public function create()
    {
        return view($this->base_view_path.'create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|min:3',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'type' => $validated['type'],
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            $data['url'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($data);

        return redirect()->route('dashboard.banners.index')
            ->with('success', 'Banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        return view('pages.admin.banner.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'type' => $validated['type'],
            'is_active' => $validated['is_active'],
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->url) {
                Storage::disk('public')->delete($banner->url);
            }
            $data['url'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('dashboard.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->url) {
            Storage::disk('public')->delete($banner->url);
        }
        $banner->is_active = false;
        $banner->save();
        $banner->delete();

        return redirect()->route('dashboard.banners.index')
            ->with('success', 'Banner deleted successfully.');
    }
}
