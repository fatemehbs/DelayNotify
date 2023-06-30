<?php

namespace Database\Seeders;

use App\Models\Agent;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 30; $i++) {
            Agent::create([
                'name' => 'Agent '. $i,
                'email' => 'agent'. $i .'@gmail.com',
            ]);
        }
    }
}
