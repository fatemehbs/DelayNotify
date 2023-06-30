<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i=0; $i < 30; $i++) {
            Product::create([
                'vendor_id' => Vendor::inRandomOrder()->first()->id,
                'price' => rand(10000,100000),
                'status' => rand(0,1),
                'title' => 'Product '.($i+1) ,
                'description' => 'Some product description.'
            ]);
        }
    }
}
