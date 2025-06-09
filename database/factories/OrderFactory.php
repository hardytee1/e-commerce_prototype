<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'customer_id' => null, // Set in seeder
            'total_amount' => $this->faker->randomFloat(2, 10000, 1000000),
            'status' => $this->faker->randomElement(['pending_payment', 'processing', 'completed', 'cancelled']),
        ];
    }
}
