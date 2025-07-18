<?php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceFactory extends Factory
{
    protected $model = Device::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'identifier' => fake()->unique()->word(),
            'code' => fake()->unique()->word(),
            'is_active' => fake()->boolean(),
        ];
    }

    public function active(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true,
            ];
        });
    }
}
