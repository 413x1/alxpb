<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardProductBannerController extends Controller
{
    private string $base_view_path;

    private Product $product;

    public function __construct()
    {
        $this->base_view_path = 'pages.admin.product.banner.';
        $this->product = Product::latest('id')->first();
    }

    public function create(Product $product)
    {
        return view($this->base_view_path.'create', [
            'product' => $product,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|min:3',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = [
            'title' => $validated['title'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            $data['url'] = $request->file('image')->store('product-banners', 'public');
        }

        $this->product->banners()->create($data);

        return redirect()->route('dashboard.products.edit')
            ->with('success', 'Product banner created successfully.');
    }

    public function edit(Product $product, ProductBanner $banner)
    {
        return view($this->base_view_path.'edit', [
            'banner' => $banner,
            'product' => $this->product,
        ]);
    }

    public function update(Request $request, Product $product, ProductBanner $banner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->url) {
                Storage::disk('public')->delete($banner->url);
            }
            $data['url'] = $request->file('image')->store('product-banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('dashboard.products.edit')
            ->with('success', 'Product banner updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, ProductBanner $banner)
    {
        // Delete image file
        if ($banner->url && Storage::disk('public')->exists($banner->url)) {
            Storage::disk('public')->delete($banner->url);
        }

        $banner->is_active = false;
        $banner->save();
        $banner->delete();

        return response()->json([
            'success' => true,
            'message' => 'Banner deleted successfully.',
        ]);
    }
}
