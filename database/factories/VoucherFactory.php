<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VoucherFactory extends Factory
{
    protected $model = Voucher::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->word(),
            'description' => fake()->text(),
            'is_used' => fake()->boolean(),
            'is_willcard' => fake()->boolean(),
            'used_at' => null,
            'created_by' => User::factory(),
        ];
    }

    public function redeemed(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_used' => true,
                'used_at' => now(),
            ];
        });
    }

    public function available(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_used' => false,
                'used_at' => null,
            ];
        });
    }
}
