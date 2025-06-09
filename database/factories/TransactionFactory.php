<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'user_id' => null, // Set in seeder
            'shop_id' => null, // Set in seeder
            'order_id' => null, // Set in seeder
            'type' => $this->faker->randomElement(['top-up', 'purchase', 'sale_credit', 'withdrawal']),
            'amount' => $this->faker->randomFloat(2, 1000, 100000),
            'description' => $this->faker->sentence(),
        ];
    }
}
