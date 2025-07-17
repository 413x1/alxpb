<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function index()
    {
        $product = Product::with(['banners'])->where('is_active', true)->first();

        return view('pages.order-form', [
            'product' => $product,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
            'payment_method' => 'required|in:qris,voucher',
            'voucher_code' => 'nullable|required_if:payment_method,voucher|string|max:255',
        ]);

        $customer = Customer::create([
            'name' => $validated['customer_name']
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $data = [
            'code' => strtoupper(uniqid('ORD-')),
            'customer_id' => $customer->getKey(),
            'product_id' => $validated['product_id'],
            'device_id' => session('active_device_id'),
            'qty' => $validated['qty'],
            'total_price' => $product->price * $validated['qty'],
            'gateway_response' => null,
            'is_voucher' => false,
            'voucher_id' => null,
            'is_active' => true,
        ];

        // Handle payment methods
        if ($validated['payment_method'] === 'voucher') {
            // Voucher code is mandatory for voucher payment
            if (empty($validated['voucher_code'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher code is required for voucher payment method',
                ], 400);
            }

            // Validate voucher
            $voucherCheck = $this->validateVoucher($validated['voucher_code']);

            if (!$voucherCheck['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $voucherCheck['message'],
                ], 400);
            }

            // Apply voucher - bypass payment
            $data['is_voucher'] = true;
            $data['voucher_id'] = $voucherCheck['voucher']->id;
            $data['status'] = 'paid'; // Direct paid status for voucher
            $data['gateway_response'] = json_encode([
                'payment_type' => 'voucher',
                'voucher_code' => $validated['voucher_code'],
                'transaction_time' => now()->toISOString(),
            ]);

            // Mark voucher as used
            $voucherCheck['voucher']->update([
                'is_used' => true,
                'used_at' => now()
            ]);

            $order = Order::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully with voucher payment',
                'order' => $order,
                'payment_method' => 'voucher'
            ]);

        } else if ($validated['payment_method'] === 'qris') {
            // QRIS Payment - need Midtrans
            $data['status'] = 'pending';
            $order = Order::create($data);

            // Configure Midtrans
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

            // Create Midtrans transaction
            $params = array(
                'transaction_details' => array(
                    'order_id' => $order->code,
                    'gross_amount' => $order->total_price,
                )
            );

            $snap_token = Snap::getSnapToken($params);

            $order->snap_token = $snap_token;
            $order->save();

            $order->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order' => $order,
                'payment_method' => 'qris'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid payment method',
        ], 400);
    }

    public function updateOrderStatus(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,code',
            'gross_amount' => 'required|numeric|min:0',
            'payment_gateway_response' => 'required|string',
        ]);

        $order = Order::where('code', $validated['order_id'])->firstOrFail();

        // Update order status based on gross amount
        $order->status = 'paid';
        $order->gateway_response = $validated['payment_gateway_response'];
        $order->save();

        $order->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'order' => $order,
        ]);
    }

    private function validateVoucher($voucherCode)
    {
        $voucher = Voucher::whereCode($voucherCode)->first();

        if (!$voucher) {
            return [
                'success' => false,
                'message' => 'Voucher code not found',
            ];
        }

        if ($voucher->is_used || !is_null($voucher->used_at)) {
            return [
                'success' => false,
                'message' => 'Voucher has already been used',
            ];
        }

        return [
            'success' => true,
            'voucher' => $voucher,
            'message' => 'Voucher is valid',
        ];
    }

    // Remove the checkVoucher method as it's no longer needed
}
