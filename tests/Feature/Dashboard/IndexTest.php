<?php

use App\Models\Banner;
use App\Models\Device;
use App\Models\Order;
use App\Models\User;

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

    it('displays correct counts for all models', function () {
        // Arrange
        $banner = Banner::factory()->count(4)->create();
        $device = Device::factory()->count(2)->create();
        $user = User::factory()->count(2)->create();
        $order = Order::factory()->count(10)->recycle($device)->create();

        // Act & Assert
        get(route('dashboard.index'))
            ->assertOk()
            ->assertViewIs('pages.admin.index.index')
            ->assertViewHas('banner', 4)
            ->assertViewHas('device', 2)
            ->assertViewHas('user', 3)
            ->assertViewHas('order', 10);
    });

    it('displays zero counts when no records exist', function () {
        // Ensure all tables are empty
        Banner::query()->delete();
        Device::query()->delete();
        User::query()->delete();
        Order::query()->delete();

        // Act & Assert
        get(route('dashboard.index'))
            ->assertOk()
            ->assertViewIs('pages.admin.index.index')
            ->assertViewHas('banner', 0)
            ->assertViewHas('device', 0)
            ->assertViewHas('user', 0)
            ->assertViewHas('order', 0);
    });
});
