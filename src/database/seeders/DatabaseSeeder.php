<?php

namespace Database\Seeders;

use App\Models\BatteryLevel;
use App\Models\Measurement;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Node;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        if (Node::count() === 0) {
            Node::factory()
                ->has(Measurement::factory()->count(10))
                ->has(BatteryLevel::factory()->count(1))
                ->count(10)
                ->create();
        } else {
            Node::all()->each(function ($node) {
                Measurement::factory()->count(10)->create([
                    'node_id' => $node->id,
                ]);
                BatteryLevel::factory()->count(1)->create([
                    'node_id' => $node->id,
                ]);
            });
        }
    }
}
