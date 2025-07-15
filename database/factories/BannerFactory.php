<?php

namespace Database\Factories;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'url' => fake()->url(),
            'type' => fake()->word(),
            'is_active' => fake()->boolean(),
        ];
    }
}
