<?php

namespace Tests\Feature\App;

use App\Classes\DeliveryTimeService;
use App\Models\Order;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\TestCase;

class DelayReportControllerTest extends TestCase
{
    use RefreshDatabase;
    use MockeryPHPUnitIntegration;

    public function testReportDelayWithValidDataReturnsSuccessResponse()
    {
        $userId = User::factory()->create()->id;
        $order = Order::factory()->create(['delivery_time' => 60, 'created_at' => now()->subMinutes(120)]);

        $orderId = $order->id;
        Trip::factory()->create(['order_id' => $orderId, 'status' => Trip::STATUS_AT_VENDOR]);

        $response = $this->post("api/v1.0/report_delay/{$orderId}", [
            'user_id' => $userId,
        ]);
        $response->assertStatus(200)
            ->assertJson(['message' => 'با موفقیت ثبت شد']);
    }

    public function testReportDelayWithInvalidUserIdReturnsErrorResponse()
    {
        $order = Order::factory()->create(['delivery_time' => 60, 'created_at' => now()->subMinutes(120)]);
        $orderId = $order->id;

        $response = $this->postJson("api/v1.0/report_delay/{$orderId}", []);
        $response->assertStatus(400)
            ->assertJson([
                'errorMessage' => 'یوزرآیدی رو حتما باید وارد کنی',
                'errorCode' => -1,
            ]);
    }

    public function testReportDelayBeforeDeliveryTimeReturnsErrorResponse()
    {
        $userId = User::factory()->create()->id;

        $order = Order::factory()->create([
            'delivery_time' => 60,
            'created_at' => now()->addMinutes(30)
        ]);

        $orderId = $order->id;

        $response = $this->post("api/v1.0/report_delay/{$orderId}", [
            'user_id' => $userId,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'errorMessage' => 'زمان تحویل سفارش هنوز فرا نرسیده',
                'errorCode' => -1,
            ]);
    }

    public function testReportDelayWhenOrderAlreadyInDelayQueue() {

        $userId = User::factory()->create()->id;

        $order = Order::factory()->create(['delivery_time' => 60, 'created_at' => now()->subMinutes(120)]);

        $orderId = $order->id;

        $response = $this->post("api/v1.0/report_delay/{$orderId}", [
            'user_id' => $userId,
        ]);
        // Check order is not re-queued
        Cache::shouldReceive('enqueue')->never();
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'با موفقیت ثبت شد'
            ]);
    }

    public function testReportDelayEstimatesNewDeliveryTime() {

        $userId = User::factory()->create()->id;

        $order = Order::factory()->create(['delivery_time' => 60, 'created_at' => now()->subMinutes(120)]);

        $orderId = $order->id;

        Trip::factory()->create([
            'order_id' => $orderId,
            'status' => Trip::STATUS_AT_VENDOR
        ]);

        // Mock estimating new delivery time
;
        $this->mock(DeliveryTimeService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getEstimatedDeliveryTime')->andReturn(90);
        });
        $response = $this->post("api/v1.0/report_delay/{$orderId}", [
            'user_id' => $userId
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'delivery_time' => 90
        ]);
    }

    public function testReportDelayDataReturnsSuccessResponse()
    {
        $userId = User::factory()->create()->id;
        $order = Order::factory()->create(['delivery_time' => 60, 'created_at' => now()->subMinutes(120)]);
        $orderId = $order->id;

        Trip::factory()->create(['order_id' => $orderId, 'status' => Trip::STATUS_AT_VENDOR]);

        $response = $this->post("api/v1.0/report_delay/{$orderId}", [
            'user_id' => $userId,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'با موفقیت ثبت شد']);

        $this->assertDatabaseHas('delay_reports', [
            'order_id' => $orderId,
            'user_id' => $userId,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'in_delay_queue' => false,
        ]);
    }

    public function testReportDelayReturnsErrorResponse()
    {
        $order = Order::factory()->create(['delivery_time' => 60, 'created_at' => now()->subMinutes(120)]);
        $orderId = $order->id;

        $response = $this->postJson("api/v1.0/report_delay/{$orderId}", [
            'user_id' => 'invalid',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'errorMessage' => 'یوزرآیدی رو حتما باید وارد کنی',
                'errorCode' => -1,
            ]);

        $this->assertDatabaseMissing('delay_reports', [
            'order_id' => $orderId,
        ]);
    }
}
