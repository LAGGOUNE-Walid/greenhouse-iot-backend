<?php

namespace App\Mqtt\Factory;

use App\Contracts\TopicHandlerInterface;
use App\Mqtt\Handlers\SoilMoistureHandler;
use App\Mqtt\Factory\MqttEventHandlerFactory;

class SoilHandlerFactory extends MqttEventHandlerFactory
{
    public function getHandler(): TopicHandlerInterface
    {
        return new SoilMoistureHandler;
    }
}
