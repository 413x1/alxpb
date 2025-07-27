<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

            // Use database transaction with atomic voucher checking and updating
            try {
                $result = DB::transaction(function () use ($validated, $data) {
                    // Check and update voucher atomically for non-wildcard vouchers
                    $voucher = Voucher::where('code', $validated['voucher_code'])->first();

                    // Validate voucher existence
                    if (!$voucher) {
                        throw new \Exception('Voucher code not found');
                    }

                    // Check if the voucher is already used (for both wildcard and non-wildcard)
                    if ($voucher->is_used || !is_null($voucher->used_at)) {
                        throw new \Exception('Voucher has already been used');
                    }

                    // For non-wildcard vouchers, use atomic update with where conditions
                    if (!$voucher->is_willcard) {
                        // Try to atomically update voucher from unused to used
                        $updated = Voucher::where('id', $voucher->id)
                            ->where('is_used', false)
                            ->whereNull('used_at')
                            ->update([
                                'is_used' => true,
                                'used_at' => now()
                            ]);

                        // If no rows were updated, voucher was already used (race condition)
                        if ($updated === 0) {
                            throw new \Exception('Voucher has already been used');
                        }
                    }

                    // Apply voucher - bypass payment
                    $data['is_voucher'] = true;
                    $data['voucher_id'] = $voucher->id;
                    $data['status'] = 'paid'; // Direct paid status for voucher

                    // Create order
                    $order = Order::create($data);

                    return [
                        'success' => true,
                        'order' => $order,
                        'voucher' => $voucher
                    ];
                });

                return response()->json([
                    'success' => true,
                    'message' => 'Order created successfully with voucher payment',
                    'order' => $result['order'],
                    'payment_method' => 'voucher'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }

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
}
