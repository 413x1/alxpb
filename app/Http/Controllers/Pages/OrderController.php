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
            'voucher_code' => 'nullable|string|max:255',
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
            'status' => 'pending',
            'qty' => $validated['qty'],
            'total_price' => $product->price * $validated['qty'],
            'gateway_response' => null,
            'is_voucher' => false,
            'voucher_id' => null,
            'is_active' => true,
        ];

        // Check voucher if provided
        if (!empty($validated['voucher_code'])) {
            $voucherCheck = $this->validateVoucher($validated['voucher_code'], $validated['qty'], $product->price);

            if ($voucherCheck['success']) {
                // Apply voucher
                $data['is_voucher'] = true;
                $data['voucher_id'] = $voucherCheck['voucher']->id;
                $data['total_price'] = $voucherCheck['final_price'];

                // Mark voucher as used
                $voucherCheck['voucher']->update([
                    'is_used' => true,
                    'used_at' => now()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $voucherCheck['message'],
                ], 400);
            }
        }

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
        ]);
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

    private function validateVoucher($voucherCode, $qty, $price)
    {
        $voucher = Voucher::whereCode($voucherCode)->first();

        if (!$voucher) {
            return [
                'success' => false,
                'message' => 'Voucher not found',
            ];
        }

        if ($voucher->is_used || !is_null($voucher->used_at)) {
            return [
                'success' => false,
                'message' => 'Voucher has already been used',
            ];
        }

        $totalPrice = $price * $qty;
        $discount = $totalPrice * (50 / 100);
        $finalPrice = $totalPrice - $discount;

        return [
            'success' => true,
            'voucher' => $voucher,
            'price' => $totalPrice,
            'discount' => $discount,
            'final_price' => $finalPrice,
            'message' => 'Voucher is valid',
        ];
    }

    public function checkVoucher(Request $request)
    {
        $validated = $request->validate([
            'voucher_code' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $result = $this->validateVoucher($validated['voucher_code'], $validated['qty'], $validated['price']);

        if ($result['success']) {
            return response()->json([
                'data' => [
                    'price' => $result['price'],
                    'discount' => $result['discount'],
                    'final_price' => $result['final_price'],
                ],
                'success' => true,
                'message' => $result['message'],
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], $result['message'] === 'Voucher not found' ? 404 : 400);
        }
    }
}
