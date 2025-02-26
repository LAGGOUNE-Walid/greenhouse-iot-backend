<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\Facades\MQTT;
use App\Events\MqttBatteryMessageReceived;

class MqttSubscribeBattery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mqtt-subscribe-battery';

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
        $mqtt->subscribe(config('mqtt-client.connections.default.battery_channel'), function (string $topic, string $message) {
            // {'node_id' : 1, 'batteryLevel' : 0.32} 
            //
            MqttBatteryMessageReceived::dispatch($message);
        }, 1);
        $mqtt->loop(true);
    }
}
