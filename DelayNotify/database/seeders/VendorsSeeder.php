<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i=0; $i < 30; $i++) {
            Vendor::create([
                'code' => 'VEN00'.($i+1),
                'phone_number' => '0912'.rand(1000000,9999999),
                'type' => $this->getVendorType(),
                'status' => $this->getStatus(),
                'title' => 'Vendor '.($i+1),
                'description' => 'Some description',
                'address' => 'Address '.($i+1),
                'city' => 'Tehran',
                'banner_image' => 'image'.($i+1).'.jpg',
                'delivery_fee' => rand(10000,20000)
            ]);
        }
    }

    public function getVendorType(): string
    {
        $types = [Vendor::TYPE_CAFE, vendor::TYPE_RESTAURANT, Vendor::TYPE_STORE];
        return $types[array_rand($types)];
    }

    public function getStatus(): string
    {
        $statuses = [Vendor::STATUS_ACTIVE, vendor::STATUS_INACTIVE];
        return $statuses[array_rand($statuses)];
    }
}
