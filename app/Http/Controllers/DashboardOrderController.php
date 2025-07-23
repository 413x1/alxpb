<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DashboardOrderController extends Controller
{
    private string $base_view_path;

    public function __construct()
    {
        $this->base_view_path = 'pages.admin.order.';
    }

    public function index()
    {
        return view($this->base_view_path.'index');
    }

    public function edit(Order $order)
    {
        // Load relationships for display
        $order->load(['customer', 'product', 'device', 'voucher']);

        return view($this->base_view_path.'edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,failed,cancelled',
        ]);

        $order->update($validated);

        return redirect()->route('dashboard.orders.index')
            ->with('success', 'Order status updated successfully.');
    }

    public function destroy(Order $order)
    {
        try {
            $order->is_active = false;
            $order->save();

            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order: '.$e->getMessage(),
            ], 500);
        }
    }
}
