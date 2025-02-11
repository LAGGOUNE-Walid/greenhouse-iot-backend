<?php

namespace App\Mqtt\Factory;

use App\Mqtt\Handlers\AirHandler;
use App\Contracts\TopicHandlerInterface;

class AirHandlerFactory extends MqttEventHandlerFactory
{
    public function getHandler(): TopicHandlerInterface
    {
        return new AirHandler;
    }
}
