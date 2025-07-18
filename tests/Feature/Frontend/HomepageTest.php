<?php

use App\Models\Banner;

beforeEach(function () {
    loginDevice();
});

it('can render homepage', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertViewIs('pages.index');
});

it('passes active banners to view', function () {
    $activeBanner = Banner::factory()->active()->create();

    $inactiveBanner = Banner::factory()->inactive()->create();

    $response = $this->get(route('home'));

    $response->assertViewHas('banners');
    $banners = $response->viewData('banners');

    expect($banners)->toHaveCount(1)
        ->and($banners->first()->name)->toBe($activeBanner->name);
});

it('orders banners by updated_at desc', function () {
    $oldBanner = Banner::factory()->active()->create([
        'name' => 'Old Banner',
        'updated_at' => now()->subDays(2),
    ]);

    $newBanner = Banner::factory()->active()->create([
        'name' => 'New Banner',
        'updated_at' => now(),
    ]);

    $response = $this->get('/');

    $banners = $response->viewData('banners');

    expect($banners->first()->name)->toBe('New Banner')
        ->and($banners->last()->name)->toBe('Old Banner');
});
