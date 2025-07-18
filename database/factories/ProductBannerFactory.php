<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductBanner;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductBannerFactory extends Factory
{
    protected $model = ProductBanner::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'title' => fake()->word(),
            'description' => fake()->text(),
            'url' => fake()->url(),
            'is_active' => fake()->boolean(),
        ];
    }
}
