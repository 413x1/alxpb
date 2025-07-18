<?php

use App\Models\Device;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

beforeEach(function () {
    login();
});

describe('Device Index', function () {
    it('can display devices list page', function () {
        // Arrange
        Device::factory()->count(5)->create();

        // Act & Assert
        get(route('dashboard.devices.index'))
            ->assertOk()
            ->assertViewIs('pages.admin.device.index')
            ->assertViewHas('devices');
    });

    it('shows empty state when no devices exist', function () {
        get(route('dashboard.devices.index'))
            ->assertOk();
    });

    it('paginates devices correctly', function () {
        // Create 15 devices (more than per page)
        Device::factory()->count(15)->create();

        get(route('dashboard.devices.index'))
            ->assertOk()
            ->assertViewHas('devices', function ($devices) {
                return $devices->count() === 10; // Per page is 10
            });
    });
});

describe('Device Create', function () {
    it('can display create device form', function () {
        $this->get(route('dashboard.devices.create'))
            ->assertOk()
            ->assertViewIs('pages.admin.device.create');
    });

    it('can create a new device', function () {
        // Arrange
        $data = [
            'name' => 'Test Device',
            'identifier' => 'DEV-001',
            'code' => 'ABC123XYZ',
            'is_active' => '1',
        ];

        // Act
        $response = $this->post(route('dashboard.devices.store'), $data);

        // Assert
        $response->assertRedirect(route('dashboard.devices.index'))
            ->assertSessionHas('success', 'Device created successfully.');

        assertDatabaseHas('devices', [
            'name' => 'Test Device',
            'identifier' => 'DEV-001',
            'code' => 'ABC123XYZ',
            'is_active' => true,
        ]);
    });

    it('creates inactive device when is_active is not checked', function () {
        $data = [
            'name' => 'Inactive Device',
            'identifier' => 'DEV-002',
            'code' => 'XYZ789ABC',
        ];

        post(route('dashboard.devices.store'), $data);

        assertDatabaseHas('devices', [
            'name' => 'Inactive Device',
            'is_active' => false,
        ]);
    });

    it('validates required fields', function () {
        post(route('dashboard.devices.store'), [])
            ->assertSessionHasErrors(['name', 'identifier', 'code']);
    });

    it('validates unique identifier', function () {
        Device::factory()->create(['identifier' => 'DEV-001']);

        post(route('dashboard.devices.store'), [
            'name' => 'Test Device',
            'identifier' => 'DEV-001',
            'code' => 'UNIQUE123',
        ])
            ->assertSessionHasErrors(['identifier']);
    });

    it('validates unique code', function () {
        Device::factory()->create(['code' => 'ABC123']);

        post(route('dashboard.devices.store'), [
            'name' => 'Test Device',
            'identifier' => 'DEV-999',
            'code' => 'ABC123',
        ])
            ->assertSessionHasErrors(['code']);
    });
});

describe('Device Edit', function () {
    it('can display edit device form', function () {
        // Arrange
        $device = Device::factory()->create();

        // Act & Assert
        get(route('dashboard.devices.edit', $device))
            ->assertOk()
            ->assertViewIs('pages.admin.device.edit')
            ->assertViewHas('device', $device);
    });

    it('can update device', function () {
        // Arrange
        $device = Device::factory()->create([
            'name' => 'Old Name',
            'identifier' => 'OLD-001',
            'code' => 'OLDCODE',
            'is_active' => true,
        ]);

        $data = [
            'name' => 'Updated Name',
            'identifier' => 'NEW-001',
            'code' => 'NEWCODE',
            'is_active' => false,
        ];

        // Act
        $response = put(route('dashboard.devices.update', $device), $data);

        // Assert
        $response->assertRedirect(route('dashboard.devices.index'))
            ->assertSessionHas('success', 'Device updated successfully.');

        assertDatabaseHas('devices', [
            'id' => $device->id,
            'name' => 'Updated Name',
            'identifier' => 'NEW-001',
            'code' => 'NEWCODE',
            'is_active' => false,
        ]);
    });

    it('validates unique identifier excluding current device', function () {
        $device1 = Device::factory()->create(['identifier' => 'DEV-001']);
        $device2 = Device::factory()->create(['identifier' => 'DEV-002']);

        // Should allow updating with same identifier
        put(route('dashboard.devices.update', $device1), [
            'name' => $device1->name,
            'identifier' => 'DEV-001',
            'code' => $device1->code,
        ])->assertSessionDoesntHaveErrors();

        // Should not allow updating with another device's identifier
        put(route('dashboard.devices.update', $device1), [
            'name' => $device1->name,
            'identifier' => 'DEV-002',
            'code' => $device1->code,
        ])->assertSessionHasErrors(['identifier']);
    });
});

describe('Device Delete', function () {
    it('can soft delete a device', function () {
        // Arrange
        $device = Device::factory()->create();

        // Act
        $response = delete(route('dashboard.devices.destroy', $device));

        // Assert
        $response->assertRedirect(route('dashboard.devices.index'))
            ->assertSessionHas('success', 'Device deleted successfully.');

        assertSoftDeleted('devices', ['id' => $device->id]);
    });
});
