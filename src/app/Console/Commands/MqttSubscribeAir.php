<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\Facades\MQTT;
use App\Events\MqttAirMessageReceived;

class MqttSubscribeAir extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mqtt-subscribe-air';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mqtt = MQTT::connection();
        $mqtt->subscribe(config("mqtt-client.connections.default.air_channel"), function (string $topic, string $message) {
            MqttAirMessageReceived::dispatch($message);
        }, 1);
        $mqtt->loop(true);
    }
}
