<?php

namespace App\Mqtt\Handlers;

use App\Contracts\MqttMessage;
use App\Contracts\TopicHandlerInterface;

class BatteryHandler implements TopicHandlerInterface
{
    public function save(MqttMessage $message): bool
    {
        return true;
    }
}
