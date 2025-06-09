<?php

namespace Database\Factories;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'order_id' => null, // Set in seeder
            'product_id' => null, // Set in seeder
            'shop_id' => null, // Set in seeder
            'quantity' => $this->faker->numberBetween(1, 5),
            'price_per_unit' => $this->faker->randomFloat(2, 1000, 100000),
        ];
    }
}
