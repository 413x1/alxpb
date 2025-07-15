<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class DashboardVoucherController extends Controller
{
    private string $base_view_path;

    public function __construct()
    {
        $this->base_view_path = 'pages.admin.voucher.';
    }

    public function index()
    {
        return view($this->base_view_path.'index');
    }

    public function create()
    {
        return view($this->base_view_path.'create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code',
            'description' => 'required|string|max:1000',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['is_wildcard'] = false;

        Voucher::create($validated);

        return redirect()->route('dashboard.vouchers.index')
            ->with('success', 'Voucher created successfully.');
    }

    public function edit(Voucher $voucher)
    {
        return view($this->base_view_path.'edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code,'.$voucher->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $voucher->update($validated);

        return redirect()->route('dashboard.vouchers.index')
            ->with('success', 'Voucher updated successfully.');
    }

    public function destroy(Voucher $voucher)
    {
        try {
            $voucher->delete();

            return response()->json([
                'success' => true,
                'message' => 'Voucher deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete voucher: ' . $e->getMessage()
            ], 500);
        }
    }
}
