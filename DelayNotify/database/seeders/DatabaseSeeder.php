<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();
        $this->call([
            VendorsSeeder::class,
            OrderSeeder::class,
            TripSeeder::class,
            DelayReportsSeeder::class,
            ProductsSeeder::class,
            AgentSeeder::class
        ]);
//         \Repository\Models\User::factory()->create([
//             'name' => 'Test User',
//             'email' => 'test@example.com',
//         ]);
    }
}
