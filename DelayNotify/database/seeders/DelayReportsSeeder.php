<?php

namespace Database\Seeders;

use App\Models\DelayReport;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DelayReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i=0; $i < 30; $i++) {
            DelayReport::create([
                'order_id' => Order::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                'status' => $this->getRandomStatus(),
                'description' => 'Some reason for delay.',
                'estimated_delay_time' => rand(0,90)
            ]);
        }
    }

    public function getRandomStatus(): int
    {
        $statuses = [DelayReport::STATUS_RESOLVED, DelayReport::STATUS_IN_PROGRESS, DelayReport::STATUS_PENDING];
        return $statuses[array_rand($statuses)];
    }
}
