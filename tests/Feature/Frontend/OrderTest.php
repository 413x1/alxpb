<?php

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Voucher;

beforeEach(function () {
    loginDevice();
});

it('can render order form page', function () {
    Product::factory()->create(['is_active' => true]);

    $response = $this->get('/order');

    $response->assertStatus(200);
    $response->assertViewIs('pages.order-form');
});

it('passes active product with banners to view', function () {
    $product = Product::factory()->create(['is_active' => true]);
    Product::factory()->create(['is_active' => false]); // inactive product

    $response = $this->get('/order');

    $response->assertViewHas('product');
    $viewProduct = $response->viewData('product');

    expect($viewProduct->id)->toBe($product->id)
        ->and($viewProduct->is_active)->toBeTrue();
});

it('can create QRIS order successfully', function () {
    $product = Product::factory()->create(['price' => 100]);

    session(['active_device_id' => 1]);

    $response = $this->post('/order', [
        'product_id' => $product->id,
        'qty' => 2,
        'customer_name' => 'John Doe',
        'payment_method' => 'qris',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Order created successfully',
        'payment_method' => 'qris'
    ]);

    $this->assertDatabaseHas('orders', [
        'product_id' => $product->id,
        'qty' => 2,
        'total_price' => 200,
        'status' => 'pending',
        'is_active' => true,
        'is_voucher' => false,
        'voucher_id' => null,
    ]);

    $this->assertDatabaseHas('customers', [
        'name' => 'John Doe',
    ]);

    // Check that snap_token is generated
    $order = Order::latest()->first();
    expect($order->snap_token)->not()->toBeNull();
});

it('can create voucher order successfully with valid voucher', function () {
    $product = Product::factory()->create(['price' => 100]);
    $voucher = Voucher::factory()->create([
        'code' => 'SAVE50',
        'is_used' => false,
        'used_at' => null,
        'is_willcard' => false,
    ]);

    session(['active_device_id' => 1]);

    $response = $this->post('/order', [
        'product_id' => $product->id,
        'qty' => 2,
        'customer_name' => 'John Doe',
        'payment_method' => 'voucher',
        'voucher_code' => 'SAVE50',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Order created successfully with voucher payment',
        'payment_method' => 'voucher'
    ]);

    // Check order with voucher applied (no discount calculation in controller, just original price)
    $this->assertDatabaseHas('orders', [
        'product_id' => $product->id,
        'qty' => 2,
        'total_price' => 200, // Original price without discount
        'status' => 'paid', // Paid status for voucher
        'is_active' => true,
        'is_voucher' => true,
        'voucher_id' => $voucher->id,
    ]);

    // Check voucher is marked as used
    $this->assertDatabaseHas('vouchers', [
        'id' => $voucher->id,
        'code' => 'SAVE50',
        'is_used' => true,
        'used_at' => now()->toDateTimeString(),
    ]);

    $voucher->refresh();
    expect($voucher->used_at)->not()->toBeNull();

    // Check gateway_response is set
    $order = Order::latest()->first();
    $gatewayResponse = json_decode($order->gateway_response, true);
    expect($gatewayResponse['payment_type'])->toBe('voucher')
        ->and($gatewayResponse['voucher_code'])->toBe('SAVE50');
});

it('fails to create order with invalid voucher', function () {
    $product = Product::factory()->create(['price' => 100]);

    session(['active_device_id' => 1]);

    $response = $this->post('/order', [
        'product_id' => $product->id,
        'qty' => 1,
        'customer_name' => 'John Doe',
        'payment_method' => 'voucher',
        'voucher_code' => 'INVALID123',
    ]);

    $response->assertStatus(400);
    $response->assertJson([
        'success' => false,
        'message' => 'Voucher code not found',
    ]);

    // No order should be created
    $this->assertDatabaseMissing('orders', [
        'product_id' => $product->id,
    ]);
});

it('fails to create order with already used voucher', function () {
    $product = Product::factory()->create(['price' => 100]);
    $voucher = Voucher::factory()->create([
        'code' => 'USED123',
        'is_used' => true,
        'used_at' => now(),
    ]);

    session(['active_device_id' => 1]);

    $response = $this->post('/order', [
        'product_id' => $product->id,
        'qty' => 1,
        'customer_name' => 'John Doe',
        'payment_method' => 'voucher',
        'voucher_code' => 'USED123',
    ]);

    $response->assertStatus(400);
    $response->assertJson([
        'success' => false,
        'message' => 'Voucher has already been used',
    ]);

    // No order should be created
    $this->assertDatabaseMissing('orders', [
        'product_id' => $product->id,
    ]);
});

it('fails to create voucher order without voucher code', function () {
    $product = Product::factory()->create(['price' => 100]);

    session(['active_device_id' => 1]);

    $response = $this->postJson('/order', [
        'product_id' => $product->id,
        'qty' => 1,
        'customer_name' => 'John Doe',
        'payment_method' => 'voucher',
        // Missing voucher_code - this should trigger validation error
    ]);

    $response->assertStatus(422); // Validation error
    $response->assertJsonValidationErrors(['voucher_code']);

    // Verify the specific error message
    $response->assertJsonFragment([
        'voucher_code' => ['The voucher code field is required when payment method is voucher.']
    ]);
});

it('validates required fields when creating order', function () {
    $response = $this->postJson('/order', []);

    $response->assertStatus(422); // Changed from 302 to 422 for JSON validation
    $response->assertJsonValidationErrors(['product_id', 'qty', 'customer_name', 'payment_method']);
});

it('validates payment method', function () {
    $product = Product::factory()->create();

    $response = $this->postJson('/order', [
        'product_id' => $product->id,
        'qty' => 1,
        'customer_name' => 'John Doe',
        'payment_method' => 'invalid_method',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['payment_method']);
});

it('validates product exists when creating order', function () {
    $response = $this->postJson('/order', [
        'product_id' => 999,
        'qty' => 1,
        'customer_name' => 'John Doe',
        'payment_method' => 'qris',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['product_id']);
});

it('validates minimum quantity when creating order', function () {
    $product = Product::factory()->create();

    $response = $this->postJson('/order', [
        'product_id' => $product->id,
        'qty' => 0,
        'customer_name' => 'John Doe',
        'payment_method' => 'qris',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['qty']);
});

it('calculates total price correctly for QRIS payment', function () {
    $product = Product::factory()->create(['price' => 50]);

    session(['active_device_id' => 1]);

    $response = $this->post('/order', [
        'product_id' => $product->id,
        'qty' => 3,
        'customer_name' => 'John Doe',
        'payment_method' => 'qris',
    ]);

    $order = Order::latest()->first();

    expect($order->total_price)->toBe(150)
        ->and($order->qty)->toBe(3)
        ->and($order->is_voucher)->toBe(0)
        ->and($order->status)->toBe('pending');
});

it('calculates total price correctly for voucher payment', function () {
    $product = Product::factory()->create(['price' => 60]);
    $voucher = Voucher::factory()->create([
        'code' => 'DISCOUNT50',
        'is_used' => false,
    ]);

    session(['active_device_id' => 1]);

    $response = $this->post('/order', [
        'product_id' => $product->id,
        'qty' => 4,
        'customer_name' => 'Jane Doe',
        'payment_method' => 'voucher',
        'voucher_code' => 'DISCOUNT50',
    ]);

    $order = Order::latest()->first();

    // Total price is calculated without discount in your controller
    expect($order->total_price)->toBe(240) // 4 * 60 = 240 (no discount applied)
    ->and($order->qty)->toBe(4)
        ->and($order->is_voucher)->toBe(1)
        ->and($order->voucher_id)->toBe($voucher->id)
        ->and($order->status)->toBe('paid');
});

it('can update order status after payment', function () {
    $order = Order::factory()->create([
        'code' => 'ORD-TEST123',
        'status' => 'pending',
        'total_price' => 100,
    ]);

    $paymentData = [
        'order_id' => 'ORD-TEST123',
        'gross_amount' => 100,
        'payment_gateway_response' => json_encode([
            'transaction_id' => 'TXN123',
            'payment_type' => 'qris',
            'transaction_status' => 'settlement'
        ]),
    ];

    $response = $this->put('/update-order-status', $paymentData);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Order status updated successfully',
    ]);

    $this->assertDatabaseHas('orders', [
        'code' => 'ORD-TEST123',
        'status' => 'paid',
    ]);
});

it('validates update order status request', function () {
    $response = $this->putJson('/update-order-status', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['order_id', 'gross_amount', 'payment_gateway_response']);
});

// Edge Cases
it('handles concurrent voucher usage', function () {
    $product = Product::factory()->create(['price' => 100]);
    $voucher = Voucher::factory()->create([
        'code' => 'CONCURRENT',
        'is_used' => false,
    ]);

    session(['active_device_id' => 1]);

    // First order succeeds
    $response1 = $this->post('/order', [
        'product_id' => $product->id,
        'qty' => 1,
        'customer_name' => 'Customer 1',
        'payment_method' => 'voucher',
        'voucher_code' => 'CONCURRENT',
    ]);

    $response1->assertStatus(200);

    // Second order with same voucher should fail
    $response2 = $this->post('/order', [
        'product_id' => $product->id,
        'qty' => 1,
        'customer_name' => 'Customer 2',
        'payment_method' => 'voucher',
        'voucher_code' => 'CONCURRENT',
    ]);

    $response2->assertStatus(400);
    $response2->assertJson([
        'success' => false,
        'message' => 'Voucher has already been used',
    ]);

    // Only one order should be created
    $orders = Order::where('voucher_id', $voucher->id)->get();
    expect($orders)->toHaveCount(1);
});

it('creates customer record when placing order', function () {
    $product = Product::factory()->create(['price' => 100]);

    session(['active_device_id' => 1]);

    $customerName = 'New Customer Test';

    $response = $this->post('/order', [
        'product_id' => $product->id,
        'qty' => 1,
        'customer_name' => $customerName,
        'payment_method' => 'qris',
    ]);

    $response->assertStatus(200);

    // Check customer was created
    $this->assertDatabaseHas('customers', [
        'name' => $customerName,
    ]);

    // Check order references the customer
    $customer = Customer::where('name', $customerName)->first();
    $order = Order::latest()->first();

    expect($order->customer_id)->toBe($customer->id);
});

it('generates unique order codes', function () {
    $product = Product::factory()->create(['price' => 100]);
    session(['active_device_id' => 1]);

    // Create multiple orders
    for ($i = 1; $i <= 3; $i++) {
        $this->post('/order', [
            'product_id' => $product->id,
            'qty' => 1,
            'customer_name' => "Customer {$i}",
            'payment_method' => 'qris',
        ]);
    }

    $orders = Order::latest()->take(3)->get();
    $codes = $orders->pluck('code')->toArray();

    // All codes should be unique
    expect($codes)->toHaveCount(3)
        ->and(array_unique($codes))->toHaveCount(3);

    // All codes should start with 'ORD-'
    foreach ($codes as $code) {
        expect($code)->toStartWith('ORD-');
    }
});

it('returns error for invalid payment method', function () {
    $product = Product::factory()->create(['price' => 100]);

    session(['active_device_id' => 1]);

    // This should be caught by validation, but testing the controller logic
    $response = $this->postJson('/order', [
        'product_id' => $product->id,
        'qty' => 1,
        'customer_name' => 'John Doe',
        'payment_method' => 'cash', // Invalid method
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['payment_method']);

    // Verify the specific error message
    $response->assertJsonFragment([
        'payment_method' => ['The selected payment method is invalid.']
    ]);
});
it('can using voucher is willcard many times', function () {
    $voucher = Voucher::factory()->create([
        'code' => 'WILLCARD',
        'is_used' => false,
        'used_at' => null,
        'is_willcard' => true, // This voucher can be used multiple times
    ]);

    $product = Product::factory()->create(['price' => 100]);

    session(['active_device_id' => 1]);

    $response = $this->post('/order', [
        'product_id' => $product->id,
        'qty' => 2,
        'customer_name' => 'John Doe',
        'payment_method' => 'voucher',
        'voucher_code' => 'WILLCARD',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Order created successfully with voucher payment',
        'payment_method' => 'voucher'
    ]);

    expect($voucher->refresh())
        ->is_used->toBeFalse()
        ->used_at->toBeNull();
});
// Remove the voucher check API tests since they're not in your controller
// The following tests are removed as your controller doesn't have these endpoints:
// - 'can check valid voucher via API'
// - 'returns error for invalid voucher via API'
// - 'returns error for used voucher via API'
// - 'validates required fields for voucher check'
// - 'validates minimum values for voucher check'
// - 'calculates discount correctly for different quantities'
