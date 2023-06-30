<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i=0; $i < 30; $i++) {
            Order::create([
                'user_id' => User::inRandomOrder()->first()->id,
                'status' => $this->getRandomStatus(),
                'total_price' => rand(10000,200000),
                'shipment_amount' => rand(0,50000),
                'delivery_time' => rand(0,90),
                'vendor_id' => Vendor::inRandomOrder()->first()->id,
            ]);
        }
    }

    public function getRandomStatus() {
        $statuses = [Order::STATUS_DELIVERED, Order::STATUS_WAITING, Order::STATUS_FAILED, Order::STATUS_DELAYED];
        return $statuses[array_rand($statuses)];
    }
}
