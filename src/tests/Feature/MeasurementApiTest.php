<?php

namespace Tests\Feature;

use App\Models\Measurement;
use App\Models\Node;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;

class MeasurementApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_getting_today_measurements_groupped_by_hours(): void
    {

        Node::factory()
            ->has(Measurement::factory()->count(10))
            ->count(10)
            ->create();

        $response = $this->get('/api/measurements', ['Accept' => 'text/event-stream']);

        $response->assertStatus(200);
    }

    public function test_getting_this_week_measurements_groupped_by_days(): void
    {
        Node::factory()
            ->has(Measurement::factory()->count(10))
            ->count(10)
            ->create();

        $response = $this->get('/api/measurements?interval=7');

        $response->assertStatus(200);
        $response->assertJsonCount(7, 'data');
    }

    public function test_getting_this_month_measurements_groupped_by_days(): void
    {
        Node::factory()
            ->has(Measurement::factory()->count(10))
            ->count(10)
            ->create();

        $response = $this->get('/api/measurements?interval=30');

        $response->assertStatus(200);
        $response->assertJsonCount(30, 'data');
    }
}
