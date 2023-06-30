<?php

namespace Database\Factories;

use App\Models\DelayReport;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DelayReport>
 */
class DelayReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => function () {
                return Order::factory()->create()->id;
            },
            'user_id' => function() {
                return User::factory()->create()->id;
            },
            'status' => $this->faker->randomElement([
                DelayReport::STATUS_PENDING,
                DelayReport::STATUS_IN_PROGRESS,
                DelayReport::STATUS_RESOLVED
            ]),
            'description' => $this->faker->sentence,
            'estimated_delay_time' => rand(0,90)
        ];
    }
}
