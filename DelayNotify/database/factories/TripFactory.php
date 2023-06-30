<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => function() {
                return Order::factory()->create()->id;
            },
            'status' => $this->faker->randomElement([
                Trip::STATUS_ASSIGNED,
                Trip::STATUS_AT_VENDOR,
                Trip::STATUS_PICKED,
                Trip::STATUS_DELIVERED
            ]),
            'rider_name' => $this->faker->name,
        ];
    }
}
