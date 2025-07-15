<?php

use App\Models\Product;
use App\Models\ProductBanner;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\put;

beforeEach(function () {
    login();
});

describe('Product Index', function () {
    it('can display product page', function () {
        // Arrange
        Product::factory()->create(['is_active' => true]);

        // Act & Assert
        get(route('dashboard.products.index'))
            ->assertOk()
            ->assertViewIs('pages.admin.product.index')
            ->assertViewHas('product');
    });

    it('shows product with banners relationship loaded', function () {
        // Arrange
        $product = Product::factory()->create(['is_active' => true]);
        ProductBanner::factory()->count(3)->create(['product_id' => $product->id]);

        get(route('dashboard.products.index'))
            ->assertOk()
            ->assertViewHas('product', function ($viewProduct) {
                return $viewProduct->relationLoaded('banners');
            });
    });
});

describe('Product Edit', function () {
    it('can display edit product form', function () {
        // Arrange
        $product = Product::factory()->create(['is_active' => true]);

        // Act & Assert
        get(route('dashboard.products.edit'))
            ->assertOk()
            ->assertViewIs('pages.admin.product.edit')
            ->assertViewHas('product')
            ->assertViewHas('banners');
    });

    it('loads product banners with pagination', function () {
        // Arrange
        $product = Product::factory()->create(['is_active' => true]);
        ProductBanner::factory()->count(15)->create(['product_id' => $product->id]);

        get(route('dashboard.products.edit'))
            ->assertOk()
            ->assertViewHas('banners', function ($banners) {
                return $banners instanceof \Illuminate\Pagination\LengthAwarePaginator;
            });
    });

    it('shows banners belonging to the active product', function () {
        // Arrange
        $activeProduct = Product::factory()->create(['is_active' => true, 'id' => 1]);
        $inactiveProduct = Product::factory()->create(['is_active' => false, 'id' => 2]);

        $activeBanner = ProductBanner::factory()->create(['product_id' => $activeProduct->id]);
        $inactiveBanner = ProductBanner::factory()->create(['product_id' => $inactiveProduct->id]);

        get(route('dashboard.products.edit'))
            ->assertOk()
            ->assertViewHas('banners', function ($banners) use ($activeBanner, $inactiveBanner) {
                return $banners->contains('id', $activeBanner->id) &&
                    !$banners->contains('id', $inactiveBanner->id);
            });
    });
});

describe('Product Update', function () {
    it('can update the active product', function () {
        // Arrange
        $product = Product::factory()->create([
            'name' => 'Old Product Name',
            'description' => 'Old description',
            'price' => 99.99,
            'is_active' => true,
        ]);

        $data = [
            'name' => 'Updated Product Name',
            'description' => 'Updated description',
            'price' => '199.99',
            'is_active' => true,
        ];

        // Act
        $response = put(route('dashboard.products.update'), $data);

        // Assert
        $response->assertRedirect(route('dashboard.products.index'))
            ->assertSessionHas('success', 'Product updated successfully.');

        assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
            'description' => 'Updated description',
            'price' => 199.99,
            'is_active' => true,
        ]);
    });

    it('validates required fields', function () {
        // Arrange
        Product::factory()->create(['is_active' => true]);

        put(route('dashboard.products.update'), [])
            ->assertSessionHasErrors(['name', 'price']);
    });

    it('validates price is numeric', function () {
        // Arrange
        Product::factory()->create(['is_active' => true]);

        put(route('dashboard.products.update'), [
            'name' => 'Valid Product Name',
            'price' => 'not-a-number',
        ])
            ->assertSessionHasErrors(['price']);
    });

    it('validates price minimum value', function () {
        // Arrange
        Product::factory()->create(['is_active' => true]);

        put(route('dashboard.products.update'), [
            'name' => 'Valid Product Name',
            'price' => '-10',
        ])
            ->assertSessionHasErrors(['price']);
    });

    it('validates unique name excluding current product', function () {
        // Arrange
        $currentProduct = Product::factory()->create(['name' => 'Current Product', 'is_active' => true]);
        $otherProduct = Product::factory()->create(['name' => 'Other Product', 'is_active' => false]);

        // Should allow updating with same name
        put(route('dashboard.products.update'), [
            'name' => 'Current Product',
            'price' => '99.99',
        ])->assertSessionDoesntHaveErrors();

        // Should not allow updating with another product's name
        put(route('dashboard.products.update'), [
            'name' => 'Other Product',
            'price' => '99.99',
        ])->assertSessionHasErrors(['name']);
    });

    it('handles is_active boolean conversion', function () {
        // Arrange
        $product = Product::factory()->create(['is_active' => true]);

        // Test with boolean true
        put(route('dashboard.products.update'), [
            'name' => $product->name,
            'price' => $product->price,
            'is_active' => true,
        ]);

        assertDatabaseHas('products', [
            'id' => $product->id,
            'is_active' => true,
        ]);
    });

    it('accepts valid decimal prices', function () {
        // Arrange
        Product::factory()->create(['is_active' => true]);

        $validPrices = ['10.99', '0.50', '999.99', '1000'];

        foreach ($validPrices as $price) {
            put(route('dashboard.products.update'), [
                'name' => "Product with price {$price}",
                'price' => $price,
            ])->assertSessionDoesntHaveErrors(['price']);
        }
    });

    it('updates only the first active product', function () {
        // Arrange
        $firstProduct = Product::factory()->create(['name' => 'First Product', 'is_active' => true, 'id' => 1]);
        $secondProduct = Product::factory()->create(['name' => 'Second Product', 'is_active' => true, 'id' => 2]);

        // Act
        put(route('dashboard.products.update'), [
            'name' => 'Updated Name',
            'price' => '199.99',
        ]);

        // Assert - Only first product should be updated
        assertDatabaseHas('products', [
            'id' => $firstProduct->id,
            'name' => 'Updated Name',
        ]);

        assertDatabaseHas('products', [
            'id' => $secondProduct->id,
            'name' => 'Second Product', // Should remain unchanged
        ]);
    });
});
