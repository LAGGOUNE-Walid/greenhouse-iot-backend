<?php

namespace App\Listeners;

use App\Events\MqttAirMessageReceived;
use App\Events\MqttSoilMessageReceived;
use App\Mqtt\Factory\AirHandlerFactory;
use App\Mqtt\Factory\SoilHandlerFactory;
use Illuminate\Queue\InteractsWithQueue;
use App\Mqtt\Handlers\SoilMoistureHandler;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageReceivedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MqttAirMessageReceived|MqttSoilMessageReceived $event): void
    {
        $handlerFactory = match (get_class($event)) {
            MqttAirMessageReceived::class => new AirHandlerFactory,
            MqttSoilMessageReceived::class => new SoilHandlerFactory,
        };
        if (! json_validate($event->message)) {
            throw new \Exception("Message $event->message is not a valid json");
        }
        $handlerFactory->handle(json_decode($event->message, true));
    }
}
