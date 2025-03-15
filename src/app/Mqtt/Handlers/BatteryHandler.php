<?php

namespace App\Mqtt\Handlers;

use App\Contracts\MqttMessage;
use App\Contracts\TopicHandlerInterface;
use App\Models\BatteryLevel;

class BatteryHandler implements TopicHandlerInterface
{
    public function save(MqttMessage $message): bool
    {
        return BatteryLevel::create($message->toElequentArray());
    }
}
