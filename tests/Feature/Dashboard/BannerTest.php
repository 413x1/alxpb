<?php

use App\Models\Banner;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

beforeEach(function () {
    login();

    Storage::fake('public');
});

describe('Banner Index', function () {
    it('can display banners list page', function () {
        // Arrange
        Banner::factory()->count(5)->create();

        // Act & Assert
        get(route('dashboard.banners.index'))
            ->assertOk()
            ->assertViewIs('pages.admin.banner.index')
            ->assertViewHas('banners');
    });

    it('shows empty state when no banners exist', function () {
        get(route('dashboard.banners.index'))
            ->assertOk();
    });

    it('paginates banners correctly', function () {
        // Create 15 banners (more than per page)
        Banner::factory()->count(15)->create();

        get(route('dashboard.banners.index'))
            ->assertOk()
            ->assertViewHas('banners', function ($banners) {
                return $banners->count() === 10; // Per page is 10
            });
    });
});

describe('Banner Create', function () {
    it('can display create banner form', function () {
        get(route('dashboard.banners.create'))
            ->assertOk()
            ->assertViewIs('pages.admin.banner.create');
    });

    it('can create a new banner', function () {
        // Arrange
        $image = UploadedFile::fake()->image('banner.jpg');
        $data = [
            'name' => 'Test Banner',
            'type' => 'main',
            'image' => $image,
            'is_active' => '1',
        ];

        // Act
        $response = $this->post(route('dashboard.banners.store'), $data);

        // Assert
        $response->assertRedirect(route('dashboard.banners.index'))
            ->assertSessionHas('success', 'Banner created successfully.');

        $this->assertDatabaseHas('banners', [
            'name' => 'Test Banner',
            'type' => 'main',
            'is_active' => true,
        ]);

        $banner = Banner::first();
        expect($banner->url)->not->toBeNull();
        Storage::disk('public')->assertExists($banner->url);
    });

    it('creates inactive banner when is_active is not checked', function () {
        $image = UploadedFile::fake()->image('banner.jpg');
        $data = [
            'name' => 'Inactive Banner',
            'type' => 'sidebar',
            'image' => $image,
        ];

        post(route('dashboard.banners.store'), $data);

        $this->assertDatabaseHas('banners', [
            'name' => 'Inactive Banner',
            'is_active' => false,
        ]);
    });

    it('validates required fields', function () {
        post(route('dashboard.banners.store'), [])
            ->assertSessionHasErrors(['name', 'type', 'image']);
    });

    it('validates image file type', function () {
        $file = UploadedFile::fake()->create('document.pdf', 1000);

        post(route('dashboard.banners.store'), [
            'name' => 'Test Banner',
            'type' => 'main',
            'image' => $file,
        ])
            ->assertSessionHasErrors(['image']);
    });

    it('validates image file size', function () {
        $image = UploadedFile::fake()->image('banner.jpg')->size(3000); // 3MB

        post(route('dashboard.banners.store'), [
            'name' => 'Test Banner',
            'type' => 'main',
            'image' => $image,
        ])
            ->assertSessionHasErrors(['image']);
    });
});

describe('Banner Edit', function () {
    it('can display edit banner form', function () {
        // Arrange
        $banner = Banner::factory()->create();

        // Act & Assert
        get(route('dashboard.banners.edit', $banner))
            ->assertOk()
            ->assertViewIs('pages.admin.banner.edit')
            ->assertViewHas('banner', $banner);
    });

    it('can update banner without changing image', function () {
        // Arrange
        $banner = Banner::factory()->create([
            'name' => 'Old Name',
            'type' => 'main',
            'is_active' => true,
        ]);

        $data = [
            'name' => 'Updated Name',
            'type' => 'sidebar',
            'is_active' => false,
        ];

        // Act
        $response = put(route('dashboard.banners.update', $banner), $data);

        // Assert
        $response->assertRedirect(route('dashboard.banners.index'))
            ->assertSessionHas('success', 'Banner updated successfully.');

        $this->assertDatabaseHas('banners', [
            'id' => $banner->id,
            'name' => 'Updated Name',
            'type' => 'sidebar',
            'is_active' => false,
            'url' => $banner->url, // Image should remain same
        ]);
    });

    it('can update banner with new image', function () {
        // Arrange
        $oldImage = UploadedFile::fake()->image('old.jpg');
        Storage::disk('public')->put('banners/old.jpg', $oldImage);

        $banner = Banner::factory()->create([
            'url' => 'banners/old.jpg',
        ]);

        $newImage = UploadedFile::fake()->image('new.jpg');
        $data = [
            'name' => $banner->name,
            'type' => $banner->type,
            'image' => $newImage,
            'is_active' => $banner->is_active,
        ];

        // Act
        put(route('dashboard.banners.update', $banner), $data);

        // Assert
        $banner->refresh();
        expect($banner->url)->not->toBe('banners/old.jpg');
        Storage::disk('public')->assertExists($banner->url);
    });

    it('validates required fields on update', function () {
        $banner = Banner::factory()->create();

        put(route('dashboard.banners.update', $banner), [])
            ->assertSessionHasErrors(['name', 'type']);
    });
});

describe('Banner Delete', function () {
    it('can delete a banner', function () {
        // Arrange
        $image = UploadedFile::fake()->image('banner.jpg');
        Storage::disk('public')->put('banners/test.jpg', $image);

        $banner = Banner::factory()->create([
            'url' => 'banners/test.jpg',
        ]);

        // Act
        $response = delete(route('dashboard.banners.destroy', $banner));

        // Assert
        $response->assertRedirect(route('dashboard.banners.index'))
            ->assertSessionHas('success', 'Banner deleted successfully.');

        assertSoftDeleted('banners', ['id' => $banner->id]);
    });
});
