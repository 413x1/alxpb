<?php

use App\Models\Device;

it('can render lock page', function () {
    $response = $this->get(route('device.login'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.lock');
});

it('can login with valid device code', function () {
    $device = Device::factory()->create([
        'code' => 'TEST123',
        'is_active' => true,
        'name' => 'Test Device',
        'identifier' => 'device-123',
    ]);

    $response = $this->post('/device/auth', [
        'code' => 'TEST123',
    ]);

    $response->assertRedirect('/');
    $response->assertSessionHas('active_device', 'device-123');
    $response->assertSessionHas('active_device_id', $device->id);
    $response->assertSessionHas('active_device_name', 'Test Device');
});

it('cannot login with invalid device code', function () {
    $response = $this->post('/device/auth', [
        'code' => 'INVALID',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['code']);
    $response->assertSessionMissing('active_device');
});
