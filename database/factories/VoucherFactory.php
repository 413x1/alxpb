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
}
