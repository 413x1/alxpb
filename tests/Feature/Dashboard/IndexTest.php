<?php

use App\Models\Device;
use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;

use function Pest\Laravel\get;

beforeEach(function () {
    login();
});

describe('Dashboard Index', function () {
    it('can display dashboard page', function () {
        // Act & Assert
        get(route('dashboard.index'))
            ->assertOk()
            ->assertViewIs('pages.admin.index.index');
    });

    it('displays correct counts and data for all models', function () {
        // Arrange
        $devices = Device::factory()->count(3)->create();
        $users = User::factory()->count(1)->create();

        // Create orders with different statuses
        $pendingOrders = Order::factory()->count(4)->state(['status' => 'pending'])->recycle($devices)->create();
        $paidOrders = Order::factory()->count(6)->state([
            'status' => 'paid',
            'total_price' => 100000,
            'created_at' => now()->startOfMonth()->addDays(5)
        ])->recycle($devices)->create();
        $otherOrders = Order::factory()->count(2)->state(['status' => 'cancelled'])->recycle($devices)->create();

        // Create vouchers with different states
        $redeemedVouchers = Voucher::factory()->recycle($users)->count(3)->redeemed()->create();
        $availableVouchers = Voucher::factory()->recycle($users)->count(7)->available()->create();

        $totalOrders = $pendingOrders->count() + $paidOrders->count() + $otherOrders->count();
        $monthlyRevenue = $paidOrders->sum('total_price');

        // Act & Assert
        get(route('dashboard.index'))
            ->assertOk()
            ->assertViewIs('pages.admin.index.index')
            ->assertViewHas('totalOrder', $totalOrders)
            ->assertViewHas('pendingOrder', 4)
            ->assertViewHas('completedOrder', 6)
            ->assertViewHas('completedAmount', $monthlyRevenue)
            ->assertViewHas('user', 2) // 5 created + 1 authenticated user
            ->assertViewHas('voucher', 10)
            ->assertViewHas('redeemedVoucher', 3)
            ->assertViewHas('availableVoucher', 7)
            ->assertViewHas('devices', $devices);
    });

    it('devices collection includes orders count', function () {
        // Arrange
        $device1 = Device::factory()->create(['name' => 'Device 01']);
        $device2 = Device::factory()->create(['name' => 'Device 02']);

        // Create orders for devices
        Order::factory()->count(5)->recycle($device1)->create();
        Order::factory()->count(3)->recycle($device2)->create();

        // Act
        $response = get(route('dashboard.index'));

        // Assert
        $response->assertOk();

        $devices = $response->viewData('devices');
        expect($devices)->toHaveCount(2);

        $deviceWithOrders1 = $devices->where('name', 'Device 01')->first();
        $deviceWithOrders2 = $devices->where('name', 'Device 02')->first();

        expect($deviceWithOrders1->orders_count)->toBe(5);
        expect($deviceWithOrders2->orders_count)->toBe(3);
    });

    it('calculates monthly revenue correctly for current month only', function () {
        // Arrange
        $device = Device::factory()->create();

        // Create paid orders in current month
        Order::factory()->count(3)->state([
            'status' => 'paid',
            'total_price' => 50000,
            'created_at' => now()->startOfMonth()
        ])->recycle($device)->create();

        // Create paid orders in previous month (should not be counted)
        Order::factory()->count(2)->state([
            'status' => 'paid',
            'total_price' => 30000,
            'created_at' => now()->subMonth()->startOfMonth()
        ])->recycle($device)->create();

        // Act & Assert
        get(route('dashboard.index'))
            ->assertOk()
            ->assertViewHas('completedAmount', 150000); // 3 * 50000
    });

    it('handles voucher states correctly', function () {
        // Arrange - Create vouchers with different states using factory methods
        Voucher::factory()->count(5)->redeemed()->create();
        Voucher::factory()->count(8)->available()->create();
        Voucher::factory()->count(2)->state(['is_used' => false, 'used_at' => null])->create(); // Additional available vouchers

        // Act & Assert
        get(route('dashboard.index'))
            ->assertOk()
            ->assertViewHas('voucher', 15)
            ->assertViewHas('redeemedVoucher', 5)
            ->assertViewHas('availableVoucher', 10); // 8 + 2 available vouchers
    });

    it('displays zero counts when no records exist', function () {
        // Ensure all tables are empty (except authenticated user)
        Device::query()->delete();
        User::whereNot('id', auth()->id())->delete();
        Order::query()->delete();
        Voucher::query()->delete();

        // Act & Assert
        get(route('dashboard.index'))
            ->assertOk()
            ->assertViewIs('pages.admin.index.index')
            ->assertViewHas('totalOrder', 0)
            ->assertViewHas('pendingOrder', 0)
            ->assertViewHas('completedOrder', 0)
            ->assertViewHas('completedAmount', 0)
            ->assertViewHas('user', 1) // Only authenticated user
            ->assertViewHas('voucher', 0)
            ->assertViewHas('redeemedVoucher', 0)
            ->assertViewHas('availableVoucher', 0);

        $devices = get(route('dashboard.index'))->viewData('devices');
        expect($devices)->toBeEmpty();
    });

    it('handles different order statuses correctly', function () {
        // Arrange
        $device = Device::factory()->create();

        Order::factory()->count(3)->state(['status' => 'pending'])->recycle($device)->create();
        Order::factory()->count(5)->state(['status' => 'paid'])->recycle($device)->create();
        Order::factory()->count(2)->state(['status' => 'cancelled'])->recycle($device)->create();
        Order::factory()->count(1)->state(['status' => 'failed'])->recycle($device)->create();

        // Act & Assert
        get(route('dashboard.index'))
            ->assertOk()
            ->assertViewHas('totalOrder', 11)
            ->assertViewHas('pendingOrder', 3)
            ->assertViewHas('completedOrder', 5);
    });
});
