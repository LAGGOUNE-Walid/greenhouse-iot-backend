<?php

namespace App\Listeners;

use App\Events\MqttBatteryMessageReceived;
use App\Mqtt\Factory\BatteryLevelHandlerFactory;

class BatteryMessageReceivedListener
{
    /**
     * Create the event listener.
     */
    public function __construct(

    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MqttBatteryMessageReceived $event): void
    {
        $handlerFactory = new BatteryLevelHandlerFactory;
        if (! json_validate($event->message)) {
            throw new \Exception("Message $event->message is not a valid json");
        }
        $handlerFactory->handle(json_decode($event->message, true));
    }
}
