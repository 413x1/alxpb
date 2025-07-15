<?php

use App\Models\Product;
use App\Models\ProductBanner;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

beforeEach(function () {
    login();
    Storage::fake('public');
});

describe('Product Banner Create', function () {
    it('can display create product banner form', function () {
        // Arrange
        $product = Product::factory()->create();

        // Act & Assert
        get(route('dashboard.product.banners.create', $product))
            ->assertOk()
            ->assertViewIs('pages.admin.product.banner.create')
            ->assertViewHas('product', $product);
    });

    it('can create a new product banner', function () {
        // Arrange
        $latestProduct = Product::factory()->create(); // This will be the latest product used by constructor
        $file = UploadedFile::fake()->image('banner.jpg');

        $data = [
            'title' => 'Test Banner',
            'description' => 'Test banner description',
            'image' => $file,
            'is_active' => '1',
        ];

        // Act
        $response = post(route('dashboard.product.banners.store', $latestProduct), $data);

        // Assert
        $response->assertRedirect(route('dashboard.products.edit'))
            ->assertSessionHas('success', 'Product banner created successfully.');

        // Banner should be created for the latest product (constructor), not route product
        assertDatabaseHas('product_banners', [
            'title' => 'Test Banner',
            'description' => 'Test banner description',
            'product_id' => $latestProduct->id, // Constructor product
            'is_active' => true,
        ]);

        // Check if image was stored
        $banner = ProductBanner::where('title', 'Test Banner')->first();
        expect($banner->url)->toBeString();
        Storage::disk('public')->assertExists($banner->url);
    });

    it('creates inactive banner when is_active is not checked', function () {
        // Arrange
        $product = Product::factory()->create();
        $file = UploadedFile::fake()->image('banner.jpg');

        $data = [
            'title' => 'Inactive Banner',
            'description' => 'This banner is inactive',
            'image' => $file,
            // is_active not included (checkbox unchecked)
        ];

        // Act
        post(route('dashboard.product.banners.store', $product), $data);

        // Assert
        assertDatabaseHas('product_banners', [
            'title' => 'Inactive Banner',
            'is_active' => false,
        ]);
    });

    it('validates required fields', function () {
        // Arrange
        $product = Product::factory()->create();

        // Act & Assert
        post(route('dashboard.product.banners.store', $product), [])
            ->assertSessionHasErrors(['title', 'image']);
    });

    it('validates title minimum length', function () {
        // Arrange
        $product = Product::factory()->create();
        $file = UploadedFile::fake()->image('banner.jpg');

        // Act & Assert
        post(route('dashboard.product.banners.store', $product), [
            'title' => 'AB', // Too short (less than 3 characters)
            'image' => $file,
        ])
            ->assertSessionHasErrors(['title']);
    });

    it('validates image file type', function () {
        // Arrange
        $product = Product::factory()->create();
        $file = UploadedFile::fake()->create('document.pdf', 1000);

        // Act & Assert
        post(route('dashboard.product.banners.store', $product), [
            'title' => 'Test Banner',
            'image' => $file,
        ])
            ->assertSessionHasErrors(['image']);
    });

    it('validates image file size', function () {
        // Arrange
        $product = Product::factory()->create();
        $file = UploadedFile::fake()->image('large-banner.jpg')->size(3000); // 3MB

        // Act & Assert
        post(route('dashboard.product.banners.store', $product), [
            'title' => 'Test Banner',
            'image' => $file,
        ])
            ->assertSessionHasErrors(['image']);
    });

    it('accepts valid image formats', function () {
        // Arrange
        $product = Product::factory()->create();
        $validFormats = ['jpg', 'jpeg', 'png', 'gif'];

        foreach ($validFormats as $format) {
            $file = UploadedFile::fake()->image("banner.{$format}");

            // Act & Assert
            post(route('dashboard.product.banners.store', $product), [
                'title' => "Banner {$format}",
                'image' => $file,
            ])->assertSessionDoesntHaveErrors(['image']);
        }
    });
});

describe('Product Banner Edit', function () {
    it('can display edit product banner form', function () {
        // Arrange
        $routeProduct = Product::factory()->create(['id' => 1]);
        $constructorProduct = Product::factory()->create(); // Latest product
        $banner = ProductBanner::factory()->create(['product_id' => $routeProduct->id]);

        // Act & Assert
        get(route('dashboard.product.banners.edit', [$routeProduct, $banner]))
            ->assertOk()
            ->assertViewIs('pages.admin.product.banner.edit')
            ->assertViewHas('banner', $banner)
            ->assertViewHas('product'); // This will be the constructor product
    });

    it('passes constructor product to edit view not route product', function () {
        // Arrange
        $routeProduct = Product::factory()->create(['id' => 1]);
        $constructorProduct = Product::factory()->create(); // Latest by ID
        $banner = ProductBanner::factory()->create(['product_id' => $routeProduct->id]);

        // Act & Assert
        get(route('dashboard.product.banners.edit', [$routeProduct, $banner]))
            ->assertOk()
            ->assertViewHas('product', function ($viewProduct) use ($constructorProduct) {
                return $viewProduct->id === $constructorProduct->id;
            });
    });
});

describe('Product Banner Update', function () {
    it('can update product banner without changing image', function () {
        // Arrange
        Storage::disk('public')->put('old-banner.jpg', 'old content');
        $product = Product::factory()->create();
        $banner = ProductBanner::factory()->create([
            'title' => 'Original Title',
            'description' => 'Original Description',
            'url' => 'old-banner.jpg',
            'product_id' => $product->id,
        ]);

        $data = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'is_active' => '1',
        ];

        // Act
        $response = put(route('dashboard.product.banners.update', [$product, $banner]), $data);

        // Assert
        $response->assertRedirect(route('dashboard.products.edit'))
            ->assertSessionHas('success', 'Product banner updated successfully.');

        assertDatabaseHas('product_banners', [
            'id' => $banner->id,
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'url' => 'old-banner.jpg', // Image unchanged
            'is_active' => true,
        ]);

        // Old image should still exist
        Storage::disk('public')->assertExists('old-banner.jpg');
    });

    it('can update product banner with new image', function () {
        // Arrange
        Storage::disk('public')->put('old-banner.jpg', 'old content');
        $product = Product::factory()->create();
        $banner = ProductBanner::factory()->create([
            'title' => 'Original Title',
            'url' => 'old-banner.jpg',
            'product_id' => $product->id,
        ]);

        $newFile = UploadedFile::fake()->image('new-banner.jpg');

        $data = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'image' => $newFile,
            'is_active' => '1',
        ];

        // Act
        $response = put(route('dashboard.product.banners.update', [$product, $banner]), $data);

        // Assert
        $response->assertRedirect(route('dashboard.products.edit'))
            ->assertSessionHas('success', 'Product banner updated successfully.');

        $banner->refresh();
        expect($banner->title)->toBe('Updated Title')
            ->and($banner->url)->not()->toBe('old-banner.jpg');

        // Check old image was deleted and new image exists
        Storage::disk('public')->assertMissing('old-banner.jpg');
        Storage::disk('public')->assertExists($banner->url);
    });

    it('can update banner to inactive when checkbox unchecked', function () {
        // Arrange
        $product = Product::factory()->create();
        $banner = ProductBanner::factory()->create([
            'is_active' => true,
            'product_id' => $product->id,
        ]);

        // Act
        put(route('dashboard.product.banners.update', [$product, $banner]), [
            'title' => $banner->title,
            'description' => $banner->description,
            // is_active not included (checkbox unchecked)
        ]);

        // Assert
        assertDatabaseHas('product_banners', [
            'id' => $banner->id,
            'is_active' => false,
        ]);
    });

    it('validates required fields during update', function () {
        // Arrange
        $product = Product::factory()->create();
        $banner = ProductBanner::factory()->create(['product_id' => $product->id]);

        // Act & Assert
        put(route('dashboard.product.banners.update', [$product, $banner]), [])
            ->assertSessionHasErrors(['title']);
    });

    it('validates image type during update', function () {
        // Arrange
        $product = Product::factory()->create();
        $banner = ProductBanner::factory()->create(['product_id' => $product->id]);
        $file = UploadedFile::fake()->create('document.pdf', 1000);

        // Act & Assert
        put(route('dashboard.product.banners.update', [$product, $banner]), [
            'title' => 'Valid Title',
            'image' => $file,
        ])
            ->assertSessionHasErrors(['image']);
    });
});

describe('Product Banner Delete', function () {
    it('can delete a product banner with image cleanup', function () {
        // Arrange
        Storage::disk('public')->put('test-banner.jpg', 'test content');
        $product = Product::factory()->create();
        $banner = ProductBanner::factory()->create([
            'url' => 'test-banner.jpg',
            'product_id' => $product->id,
        ]);

        // Act
        $response = delete(route('dashboard.product.banners.destroy', [$product, $banner]));

        // Assert
        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Banner deleted successfully.',
            ]);

        assertSoftDeleted('product_banners', [
            'id' => $banner->id,
        ]);

        // Check image was deleted
        Storage::disk('public')->assertMissing('test-banner.jpg');
    });

    it('handles deletion when image file does not exist in storage', function () {
        // Arrange
        $product = Product::factory()->create();
        $banner = ProductBanner::factory()->create([
            'url' => 'non-existent-banner.jpg',
            'product_id' => $product->id,
        ]);

        // Act
        $response = delete(route('dashboard.product.banners.destroy', [$product, $banner]));

        // Assert
        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Banner deleted successfully.',
            ]);

        assertSoftDeleted('product_banners', [
            'id' => $banner->id,
        ]);
    });

    it('always returns json response for delete operations', function () {
        // Arrange
        $product = Product::factory()->create();
        $banner = ProductBanner::factory()->create(['product_id' => $product->id]);

        // Act
        $response = delete(route('dashboard.product.banners.destroy', [$product, $banner]));

        // Assert
        $response->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'success',
                'message'
            ]);
    });
});

describe('Product Banner Storage Logic', function () {
    it('stores image in product-banners directory', function () {
        // Arrange
        $product = Product::factory()->create();
        $file = UploadedFile::fake()->image('test-banner.jpg');

        // Act
        $response = post(route('dashboard.product.banners.store', $product), [
            'title' => 'Test Banner',
            'image' => $file,
        ]);

        // Assert
        $banner = ProductBanner::where('title', 'Test Banner')->first();
        expect($banner->url)->toStartWith('product-banners/');
        Storage::disk('public')->assertExists($banner->url);
    });

    it('uses constructor product for banner creation', function () {
        // Arrange
        $routeProduct = Product::factory()->create(['id' => 1]);
        $latestProduct = Product::factory()->create(); // This will be latest by ID
        $file = UploadedFile::fake()->image('banner.jpg');

        // Act
        post(route('dashboard.product.banners.store', $routeProduct), [
            'title' => 'Test Banner',
            'image' => $file,
        ]);

        // Assert - Banner should belong to latest product (constructor), not route product
        assertDatabaseHas('product_banners', [
            'title' => 'Test Banner',
            'product_id' => $latestProduct->id,
        ]);

        $this->assertDatabaseMissing('product_banners', [
            'title' => 'Test Banner',
            'product_id' => $routeProduct->id,
        ]);
    });
});
