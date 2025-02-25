<?php

namespace App\Console\Commands;

use App\Events\MqttSoilMessageReceived;
use Illuminate\Console\Command;
use PhpMqtt\Client\Facades\MQTT;

class MqttSubscribeSoil extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mqtt-subscribe-soil';

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
        $mqtt->subscribe(config('mqtt-client.connections.default.soil_channel'), function (string $topic, string $message) {
            MqttSoilMessageReceived::dispatch($message);
        }, 1);
        $mqtt->loop(true);
    }
}
