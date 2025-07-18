<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Device;
use App\Models\Order;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('ORD-####-????')),
            'customer_id' => Customer::factory(),
            'product_id' => Product::factory(),
            'device_id' => Device::factory(),
            'status' => fake()->randomElement(['pending', 'paid', 'failed', 'cancelled']),
            'qty' => fake()->numberBetween(1, 5),
            'total_price' => fake()->randomFloat(2, 10, 1000),
            'gateway_response' => null,
            'is_voucher' => false,
            'voucher_id' => null,
            'is_active' => true,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
        ]);
    }

    public function withVoucher(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_voucher' => true,
            'voucher_id' => Voucher::factory(),
        ]);
    }
}
