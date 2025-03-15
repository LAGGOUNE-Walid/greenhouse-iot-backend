<?php

namespace App\Mqtt\Factory;

use App\Contracts\TopicHandlerInterface;
use App\Enums\NodeType;
use App\Mqtt\Handlers\BatteryHandler;

class BatteryLevelHandlerFactory extends MqttEventHandlerFactory
{
    public function getHandler(): TopicHandlerInterface
    {
        return new BatteryHandler;
    }

    public function getNodeType(): ?NodeType
    {
        return null;
    }
}
