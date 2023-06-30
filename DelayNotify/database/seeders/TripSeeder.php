<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Trip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Order::unguard();

        $this->call(OrderSeeder::class);

        Order::reguard();

        $orders = Order::all();

        foreach ($orders as $order){
            Trip::updateOrCreate([
                'order_id' => $order->id],[
                'status' => $this->getRandomStatus(),
                'rider_name' => 'Rider '.rand(1,50)
            ]);
        }
    }

    public function getRandomStatus() {
        $statuses = [
            Trip::STATUS_ASSIGNED,
            Trip::STATUS_AT_VENDOR,
            Trip::STATUS_PICKED,
            Trip::STATUS_DELIVERED
        ];
        return $statuses[array_rand($statuses)];
    }
}
