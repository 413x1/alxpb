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
            'code' => [
                'required',
                'string',
                'size:4',  // Exactly 4 characters
                'regex:/^[A-Za-z0-9]{4}$/',  // Only uppercase letters and numbers, exactly 4 chars
                'unique:vouchers,code',
            ],
            'description' => 'nullable|string|max:1000',
            'is_willcard' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();

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
            'code' => [
                'required',
                'string',
                'size:4',  // Exactly 4 characters
                'regex:/^[A-Za-z0-9]{4}$/',  // Only uppercase letters and numbers, exactly 4 chars,
                'unique:vouchers,code,'.$voucher->id, // Unique except for the current voucher
            ],
            'description' => 'nullable|string|max:1000',
            'is_willcard' => 'nullable|boolean',
        ]);

        // Set defaults for nullable fields if they are null
        $validated['is_willcard'] = $validated['is_willcard'] ?? false;

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
                'message' => 'Voucher deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete voucher: '.$e->getMessage(),
            ], 500);
        }
    }

    public function generateVoucherCode(Request $request)
    {
        $validated = $request->validate([
            'count' => 'required|integer|min:1|max:100',
            'is_willcard' => 'boolean',
        ]);

        $count = $validated['count'];

        $isWillcard = $validated['is_willcard'] ?? false;

        try {
            $vouchers = generateVoucherCode($count, [
                'is_willcard' => $isWillcard,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vouchers generated successfully.',
                'data' => $vouchers,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate vouchers: '.$exception->getMessage(),
            ], 500);
        }
    }
}
