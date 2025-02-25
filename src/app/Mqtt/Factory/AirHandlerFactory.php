<?php

namespace App\Mqtt\Factory;

use App\Contracts\TopicHandlerInterface;
use App\Enums\NodeType;
use App\Mqtt\Handlers\AirHandler;

class AirHandlerFactory extends MqttEventHandlerFactory
{
    public function getHandler(): TopicHandlerInterface
    {
        return new AirHandler;
    }

    public function getNodeType(): NodeType
    {
        return NodeType::AIR_NODE;
    }
}
