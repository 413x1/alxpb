<?php

use App\Models\Customer;
use App\Models\Device;
use App\Models\Order;
use App\Models\Product;
use App\Models\Voucher;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\put;

beforeEach(function () {
    login();
});

describe('Order Index', function () {
    it('can display orders list page', function () {
        // Arrange
        Order::factory()->count(5)->create();

        // Act & Assert
        get(route('dashboard.orders.index'))
            ->assertOk()
            ->assertViewIs('pages.admin.order.index');
    });

    it('shows empty state when no orders exist', function () {
        get(route('dashboard.orders.index'))
            ->assertOk();
    });
});

describe('Order Edit', function () {
    it('can display edit order form', function () {
        // Arrange
        $customer = Customer::factory()->create(['name' => 'John Doe']);
        $product = Product::factory()->create(['name' => 'Test Product']);
        $device = Device::factory()->create(['name' => 'Test Device']);

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'device_id' => $device->id,
        ]);

        // Act & Assert
        get(route('dashboard.orders.edit', $order))
            ->assertOk()
            ->assertViewIs('pages.admin.order.edit')
            ->assertViewHas('order', $order);
    });

    it('loads order with relationships', function () {
        // Arrange
        $customer = Customer::factory()->create(['name' => 'Jane Smith']);
        $product = Product::factory()->create(['name' => 'Premium Product']);
        $device = Device::factory()->create(['name' => 'Device-001']);
        $voucher = Voucher::factory()->create(['code' => 'DISCOUNT10']);

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'device_id' => $device->id,
            'voucher_id' => $voucher->id,
            'is_voucher' => true,
        ]);

        // Act
        $response = get(route('dashboard.orders.edit', $order));

        // Assert
        $response->assertOk();
        $viewOrder = $response->viewData('order');

        expect($viewOrder->customer->name)->toBe('Jane Smith')
            ->and($viewOrder->product->name)->toBe('Premium Product')
            ->and($viewOrder->device->name)->toBe('Device-001')
            ->and($viewOrder->voucher->code)->toBe('DISCOUNT10');
    });

    it('can update order status', function () {
        // Arrange
        $order = Order::factory()->create([
            'status' => 'pending',
        ]);

        $data = [
            'status' => 'paid',
        ];

        // Act
        $response = put(route('dashboard.orders.update', $order), $data);

        // Assert
        $response->assertRedirect(route('dashboard.orders.index'))
            ->assertSessionHas('success', 'Order status updated successfully.');

        assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
        ]);
    });

    it('validates status field is required', function () {
        $order = Order::factory()->create();

        put(route('dashboard.orders.update', $order), [])
            ->assertSessionHasErrors(['status']);
    });

    it('validates status must be valid enum value', function () {
        $order = Order::factory()->create();

        put(route('dashboard.orders.update', $order), [
            'status' => 'invalid_status',
        ])
            ->assertSessionHasErrors(['status']);
    });

    it('accepts all valid status values', function () {
        $order = Order::factory()->create();
        $validStatuses = ['pending', 'paid', 'failed', 'cancelled'];

        foreach ($validStatuses as $status) {
            put(route('dashboard.orders.update', $order), [
                'status' => $status,
            ])->assertSessionDoesntHaveErrors();

            assertDatabaseHas('orders', [
                'id' => $order->id,
                'status' => $status,
            ]);
        }
    });

    it('can update order from pending to paid', function () {
        $order = Order::factory()->create(['status' => 'pending']);

        put(route('dashboard.orders.update', $order), [
            'status' => 'paid',
        ]);

        assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
        ]);
    });

    it('can update order from pending to cancelled', function () {
        $order = Order::factory()->create(['status' => 'pending']);

        put(route('dashboard.orders.update', $order), [
            'status' => 'cancelled',
        ]);

        assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);
    });

    it('can update order from pending to failed', function () {
        $order = Order::factory()->create(['status' => 'pending']);

        put(route('dashboard.orders.update', $order), [
            'status' => 'failed',
        ]);

        assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'failed',
        ]);
    });

    it('preserves other order fields when updating status', function () {
        $order = Order::factory()->create([
            'code' => 'ORD-12345',
            'qty' => 3,
            'total_price' => 150.00,
            'status' => 'pending',
            'is_active' => true,
        ]);

        put(route('dashboard.orders.update', $order), [
            'status' => 'paid',
        ]);

        assertDatabaseHas('orders', [
            'id' => $order->id,
            'code' => 'ORD-12345',
            'qty' => 3,
            'total_price' => 150.00,
            'status' => 'paid',
            'is_active' => true,
        ]);
    });
});

it('can delete order', function () {
    // Arrange
    $order = Order::factory()->create([
        'is_active' => true,
    ]);

    // Act
    $response = delete(route('dashboard.orders.destroy', $order));

    // Assert
    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Order deleted successfully.',
        ]);

    // Check that is_active is set to false
    assertDatabaseHas('orders', [
        'id' => $order->id,
        'is_active' => false,
    ]);

    // Check that the record is soft deleted
    assertSoftDeleted('orders', ['id' => $order->id]);
});

it('handles delete order exception', function () {
    // Arrange
    $order = Order::factory()->create();

    // Mock an exception scenario by using a non-existent order ID
    // Or you could mock the Order model to throw an exception

    // Act
    $response = delete(route('dashboard.orders.destroy', 99999));

    // Assert
    $response->assertStatus(404); // Laravel will return 404 for non-existent model
});
