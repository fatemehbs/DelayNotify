<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'vendor_id' => function () {
                return Vendor::factory()->create()->id;
            },            'total_price' => $this->faker->numberBetween(10000, 200000),
            'status' => $this->faker->randomElement([
                Order::STATUS_DELIVERED,
                Order::STATUS_WAITING,
                Order::STATUS_DELAYED,
                Order::STATUS_FAILED
            ]),
            'shipment_amount' => $this->faker->numberBetween(0, 50000),
            'delivery_time' => now()->addDays(rand(1,7))
        ];
    }
}
