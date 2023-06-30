<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->uuid,
            'phone_number' => $this->faker->e164PhoneNumber,
            'type' => $this->faker->randomElement(['Restaurant', 'Cafe', 'Store']),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'title' => $this->faker->company,
            'description' => $this->faker->text(200),
            'address' => $this->faker->address,
            'city' => 'Tehran',
            'banner_image' => $this->faker->imageUrl,
            'delivery_fee' => $this->faker->numberBetween(10000, 20000),
        ];
    }
}
