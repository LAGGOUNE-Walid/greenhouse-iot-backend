<?php

namespace Tests\Unit;

use App\Events\MqttAirMessageReceived;
use App\Events\MqttSoilMessageReceived;
use App\Listeners\MessageReceivedListener;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Event;

class MqttMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_air_message_is_saved_to_database(): void
    {
        Event::fake();
        $message = json_encode([
            'node_id' => rand(1, 10),
            'temperature' => rand(0, 30),
            'humidity' => rand(0, 100),
            'pressure' => rand(0, 10),
        ]);
        MqttAirMessageReceived::dispatch($message);
        Event::assertListening(MqttAirMessageReceived::class, MessageReceivedListener::class);

        $messageReceivedListener = new MessageReceivedListener;
        $messageReceivedListener->handle(new MqttAirMessageReceived($message));
        $this->assertDatabaseCount('measurements', 3);
        $this->assertDatabaseCount('nodes', 1);
    }

    public function test_creating_nodes(): void
    {
        Event::fake();
        $message = json_encode([
            'node_id' => 1,
            'temperature' => rand(0, 30),
            'humidity' => rand(0, 100),
            'pressure' => rand(0, 10),
        ]);
        MqttAirMessageReceived::dispatch($message);
        Event::assertListening(MqttAirMessageReceived::class, MessageReceivedListener::class);

        $messageReceivedListener = new MessageReceivedListener;
        $messageReceivedListener->handle(new MqttAirMessageReceived($message));
        $this->assertDatabaseCount('measurements', 3);
        $this->assertDatabaseCount('nodes', 1);
        $message = json_encode([
            'node_id' => 2,
            'temperature' => rand(0, 30),
            'humidity' => rand(0, 100),
            'pressure' => rand(0, 10),
        ]);
        MqttAirMessageReceived::dispatch($message);
        Event::assertListening(MqttAirMessageReceived::class, MessageReceivedListener::class);

        $messageReceivedListener = new MessageReceivedListener;
        $messageReceivedListener->handle(new MqttAirMessageReceived($message));
        $this->assertDatabaseCount('measurements', 6);
        $this->assertDatabaseCount('nodes', 2);
    }

    public function test_soil_message_is_saved_to_database(): void
    {
        Event::fake();
        $message = json_encode([
            'node_id' => rand(1, 10),
            'soil1' => rand(0, 30),
            'soil2' => rand(0, 100),
            'soil3' => rand(0, 10),
        ]);
        MqttSoilMessageReceived::dispatch($message);
        Event::assertListening(MqttSoilMessageReceived::class, MessageReceivedListener::class);

        $messageReceivedListener = new MessageReceivedListener;
        $messageReceivedListener->handle(new MqttSoilMessageReceived($message));
        $this->assertDatabaseCount('measurements', 3);
        $this->assertDatabaseCount('nodes', 1);
    }

    public function test_non_valid_json_will_not_be_treated(): void
    {
        Event::fake();
        $message = '{broken_json@#}';
        MqttAirMessageReceived::dispatch($message);
        Event::assertListening(MqttAirMessageReceived::class, MessageReceivedListener::class);

        $messageReceivedListener = new MessageReceivedListener;

        $this->assertThrows(function () use ($messageReceivedListener, $message) {
            $messageReceivedListener->handle(new MqttAirMessageReceived($message));
        }, Exception::class);
    }

    public function test_valid_but_empty_air_json_will_not_be_saved(): void
    {
        Event::fake();
        $message = json_encode([
            'node_id' => 1,
            'foo' => 'bar',
        ]);
        MqttAirMessageReceived::dispatch($message);
        Event::assertListening(MqttAirMessageReceived::class, MessageReceivedListener::class);

        $messageReceivedListener = new MessageReceivedListener;

        $this->assertThrows(function () use ($messageReceivedListener, $message) {
            $messageReceivedListener->handle(new MqttAirMessageReceived($message));
        }, Exception::class);
    }

    public function test_valid_but_empty_soil_json_will_not_be_saved(): void
    {
        Event::fake();
        $message = json_encode([
            'node_id' => 1,
        ]);
        MqttSoilMessageReceived::dispatch($message);
        Event::assertListening(MqttSoilMessageReceived::class, MessageReceivedListener::class);

        $messageReceivedListener = new MessageReceivedListener;

        $this->assertThrows(function () use ($messageReceivedListener, $message) {
            $messageReceivedListener->handle(new MqttSoilMessageReceived($message));
        }, Exception::class);
    }
}
