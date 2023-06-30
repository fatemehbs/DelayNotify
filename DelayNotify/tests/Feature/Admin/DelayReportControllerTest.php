<?php

namespace Tests\Feature\Admin;

use App\Models\Agent;
use App\Models\DelayReport;
use App\Models\Order;
use App\Utility\CacheUtils;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\TestCase;

class DelayReportControllerTest extends TestCase
{
    use RefreshDatabase;
    use MockeryPHPUnitIntegration;

    public function testAssignToAgentValidRequest()
    {
        $agentId = Agent::factory()->create()->id;
        $order = Order::factory()->create(['delivery_time' => 60, 'created_at' => now()->subMinutes(120)]);
        $this->mock(CacheUtils::class, function (MockInterface $mock) use ($order) {
            $mock->shouldReceive('dequeue')->andReturn($order->id);
        });

        $this->mock(CacheUtils::class, function (MockInterface $mock) use ($order) {
            $mock->shouldReceive('isEmpty')->andReturn(false);
        });

        $response = $this->post("/api/administration/assign_to_agent", [
            'agent_id' => $agentId
        ]);
        $response->assertStatus(200)
            ->assertJson(['message' => 'با موفقیت انجام شد']);
    }

    public function testAssignToAgentValidationError()
    {
        $response = $this->post("/api/administration/assign_to_agent", [
        ]);
        $response->assertStatus(400)
            ->assertJson([
                'errorMessage' => 'شماره کاربری رو حتما باید وارد کنی',
                'errorCode' => -1,
            ]);
    }

    public function testAssignToAgentAgentBusy()
    {
        $agentId = Agent::factory()->create()->id;
        $order = Order::factory()->create(['delivery_time' => 60, 'created_at' => now()->subMinutes(120)]);
        DelayReport::factory()->create(['agent_id' => $agentId, 'status' => DelayReport::STATUS_IN_PROGRESS]);

        $this->mock(CacheUtils::class, function (MockInterface $mock) use ($order) {
            $mock->shouldReceive('dequeue')->andReturn($order->id);
        });

        $this->mock(CacheUtils::class, function (MockInterface $mock) use ($order) {
            $mock->shouldReceive('isEmpty')->andReturn(false);
        });

        $response = $this->post("/api/administration/assign_to_agent", [
            'agent_id' => $agentId
        ]);
        $response->assertStatus(400)
            ->assertJson([
                "errorMessage" => "این کارمند در حال انجام وظیفه است",
                "errorCode" => -1
            ]);
    }

    public function testAssignNoDelayedOrders()
    {
        $agentId = Agent::factory()->create()->id;

        $this->mock(CacheUtils::class, function (MockInterface $mock) {
            $mock->shouldReceive('isEmpty')->andReturn(true);
        });

        $response = $this->post("/api/administration/assign_to_agent", [
            'agent_id' => $agentId
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'errorMessage' => 'هیچ سفارش تاخیری در صف وجود ندارد',
                'errorCode' => 400,
            ]);
    }
}
