<?php

namespace Database\Seeders;

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

        Node::factory()
            ->has(Measurement::factory()->count(10))
            ->count(10)
            ->create();
    }
}
