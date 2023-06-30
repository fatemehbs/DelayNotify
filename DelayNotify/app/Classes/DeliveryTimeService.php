<?php

namespace App\Classes;

use App\Exceptions\DeliveryTimeServiceError;
use Illuminate\Support\Facades\Http;

class DeliveryTimeService
{
    /**
     * @throws DeliveryTimeServiceError
     */
    public function getEstimatedDeliveryTime()
    {
        Http::fake([config('delivery_time_service.url') => Http::response([
            'estimated_delivery_time' => rand(5, 120)], 200)
        ]);
        $response = Http::get(config('delivery_time_service.url'));

        if ($response->successful()) {
            return $response->json('estimated_delivery_time');
        } else {
            throw new DeliveryTimeServiceError;
        }
    }
}
