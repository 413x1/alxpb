<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class DashboardProductController extends Controller
{
    private string $base_view_path;
    private Product $product;

    public function __construct()
    {
        $this->base_view_path = 'pages.admin.product.';
        $this->product = Product::with(['banners'])->where('is_active', true)->orderBy('id')->first();
    }

    public function index()
    {
        return view($this->base_view_path .'index',[
            'product' => $this->product
        ]);
    }

    public function edit()
    {
        $banners = $this->product->load('banners')->banners()->paginate();
        return view($this->base_view_path .'edit', [
            'product' => $this->product,
            'banners' => $banners
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products,name,'.$this->product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $this->product->update($validated);

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product updated successfully.');
    }
}
