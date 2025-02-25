<?php

namespace App\Mqtt\Factory;

use App\Contracts\TopicHandlerInterface;
use App\Enums\NodeType;
use App\Mqtt\Handlers\SoilMoistureHandler;

class SoilHandlerFactory extends MqttEventHandlerFactory
{
    public function getHandler(): TopicHandlerInterface
    {
        return new SoilMoistureHandler;
    }

    public function getNodeType(): NodeType
    {
        return NodeType::SOIL_NODE;
    }
}
