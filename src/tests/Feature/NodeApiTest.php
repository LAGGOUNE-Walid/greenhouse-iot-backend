<?php

namespace Tests\Feature;

use App\Models\Measurement;
use App\Models\Node;
use Illuminate\Foundation\Testing\TestCase;

class NodeApiTest extends TestCase
{
    public function test_getting_nodes_api(): void
    {
        Node::factory()
            ->has(Measurement::factory()->count(10))
            ->count(10)
            ->create();
        $response = $this->get('/api/nodes?static=1');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'type',
                    'type_name',
                    'last_battery_level',
                    'last_measurement',
                ],
            ],
        ]);
    }
}
