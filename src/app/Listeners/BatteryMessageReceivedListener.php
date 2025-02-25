<?php

namespace App\Listeners;

use App\Events\MqttBatteryMessageReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BatteryMessageReceivedListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        
    )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MqttBatteryMessageReceived $event): void
    {
        //
        dump($event);
    }
}
