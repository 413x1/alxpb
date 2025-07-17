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

it('can create order successfully without voucher', function () {
    $product = Product::factory()->create(['price' => 100]);

    session(['active_device_id' => 1]);

    $response = $this->post('/order', [
        'product_id' => $product->id,
        'qty' => 2,
        'customer_name' => 'John Doe',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Order created successfully',
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
});

it('can create order successfully with valid voucher', function () {
    $product = Product::factory()->create(['price' => 100]);
    $voucher = Voucher::factory()->create([
        'code' => 'SAVE50',
        'is_used' => false,
        'used_at' => null,
    ]);

    session(['active_device_id' => 1]);

    $response = $this->post('/order', [
        'product_id' => $product->id,
        'qty' => 2,
        'customer_name' => 'John Doe',
        'voucher_code' => 'SAVE50',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Order created successfully',
    ]);

    // Check order with voucher applied (50% discount)
    $this->assertDatabaseHas('orders', [
        'product_id' => $product->id,
        'qty' => 2,
        'total_price' => 100, // 200 - 50% = 100
        'status' => 'pending',
        'is_active' => true,
        'is_voucher' => true,
        'voucher_id' => $voucher->id,
    ]);

    // Check voucher is marked as used
    $this->assertDatabaseHas('vouchers', [
        'id' => $voucher->id,
        'code' => 'SAVE50',
        'is_used' => true,
    ]);

    $voucher->refresh();
    expect($voucher->used_at)->not()->toBeNull();
});

it('fails to create order with invalid voucher', function () {
    $product = Product::factory()->create(['price' => 100]);

    session(['active_device_id' => 1]);

    $response = $this->post('/order', [
        'product_id' => $product->id,
        'qty' => 1,
        'customer_name' => 'John Doe',
        'voucher_code' => 'INVALID123',
    ]);

    $response->assertStatus(400);
    $response->assertJson([
        'success' => false,
        'message' => 'Voucher not found',
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

it('validates required fields when creating order', function () {
    $response = $this->post('/order', []);

    $response->assertStatus(302);
    $response->assertSessionHasErrors(['product_id', 'qty', 'customer_name']);
});

it('validates product exists when creating order', function () {
    $response = $this->post('/order', [
        'product_id' => 999,
        'qty' => 1,
        'customer_name' => 'John Doe',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors(['product_id']);
});

it('validates minimum quantity when creating order', function () {
    $product = Product::factory()->create();

    $response = $this->post('/order', [
        'product_id' => $product->id,
        'qty' => 0,
        'customer_name' => 'John Doe',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors(['qty']);
});

it('calculates total price correctly without voucher', function () {
    $product = Product::factory()->create(['price' => 50]);

    session(['active_device_id' => 1]);

    $response = $this->post('/order', [
        'product_id' => $product->id,
        'qty' => 3,
        'customer_name' => 'John Doe',
    ]);

    $order = Order::latest()->first();

    expect($order->total_price)->toBe(150)
        ->and($order->qty)->toBe(3)
        ->and($order->is_voucher)->toBe(0);
});

it('calculates total price correctly with voucher', function () {
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
        'voucher_code' => 'DISCOUNT50',
    ]);

    $order = Order::latest()->first();

    // 4 * 60 = 240, with 50% discount = 120
    expect($order->total_price)->toBe(120)
        ->and($order->qty)->toBe(4)
        ->and($order->is_voucher)->toBe(1)
        ->and($order->voucher_id)->toBe($voucher->id);
});

// Voucher Check API Tests
it('can check valid voucher via API', function () {
    $voucher = Voucher::factory()->create([
        'code' => 'TEST50',
        'is_used' => false,
        'used_at' => null,
    ]);

    $response = $this->post('/check-voucher', [
        'voucher_code' => 'TEST50',
        'qty' => 2,
        'price' => 100,
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Voucher is valid',
        'data' => [
            'price' => 200,      // 100 * 2
            'discount' => 100,   // 200 * 50%
            'final_price' => 100, // 200 - 100
        ],
    ]);
});

it('returns error for invalid voucher via API', function () {
    $response = $this->post('/check-voucher', [
        'voucher_code' => 'NOTFOUND',
        'qty' => 1,
        'price' => 100,
    ]);

    $response->assertStatus(404);
    $response->assertJson([
        'success' => false,
        'message' => 'Voucher not found',
    ]);
});

it('returns error for used voucher via API', function () {
    $voucher = Voucher::factory()->create([
        'code' => 'ALREADYUSED',
        'is_used' => true,
        'used_at' => now(),
    ]);

    $response = $this->post('/check-voucher', [
        'voucher_code' => 'ALREADYUSED',
        'qty' => 1,
        'price' => 100,
    ]);

    $response->assertStatus(400);
    $response->assertJson([
        'success' => false,
        'message' => 'Voucher has already been used',
    ]);
});

it('validates required fields for voucher check', function () {
    $response = $this->post('/check-voucher', []);

    $response->assertStatus(302);
    $response->assertSessionHasErrors(['voucher_code', 'qty', 'price']);
});

it('validates minimum values for voucher check', function () {
    $response = $this->post('/check-voucher', [
        'voucher_code' => 'TEST',
        'qty' => 0,
        'price' => -10,
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors(['qty', 'price']);
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
        'voucher_code' => 'CONCURRENT',
    ]);

    $response1->assertStatus(200);

    // Second order with same voucher should fail
    $response2 = $this->post('/order', [
        'product_id' => $product->id,
        'qty' => 1,
        'customer_name' => 'Customer 2',
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

it('calculates discount correctly for different quantities', function () {
    $voucher = Voucher::factory()->create([
        'code' => 'MULTI50',
        'is_used' => false,
    ]);

    $testCases = [
        ['qty' => 1, 'price' => 100, 'expected_final' => 50],
        ['qty' => 2, 'price' => 100, 'expected_final' => 100],
        ['qty' => 3, 'price' => 50, 'expected_final' => 75],
        ['qty' => 5, 'price' => 80, 'expected_final' => 200],
    ];

    foreach ($testCases as $case) {
        $response = $this->post('/check-voucher', [
            'voucher_code' => 'MULTI50',
            'qty' => $case['qty'],
            'price' => $case['price'],
        ]);

        $response->assertStatus(200);
        $data = $response->json('data');

        expect($data['final_price'])->toBe($case['expected_final'])
            ->and($data['price'])->toBe($case['qty'] * $case['price'])
            ->and($data['discount'])->toBe((int)($case['qty'] * $case['price']) * 50 / 100);
    }
});

it('creates customer record when placing order', function () {
    $product = Product::factory()->create(['price' => 100]);

    session(['active_device_id' => 1]);

    $customerName = 'New Customer Test';

    $response = $this->post('/order', [
        'product_id' => $product->id,
        'qty' => 1,
        'customer_name' => $customerName,
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
