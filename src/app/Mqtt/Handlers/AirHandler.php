<?php

namespace App\Mqtt\Handlers;

use App\Contracts\MqttMessage;
use App\Contracts\TopicHandlerInterface;
use App\Models\Measurement;

class AirHandler implements TopicHandlerInterface
{
    public function save(MqttMessage $message): bool
    {
        return Measurement::insert($message->toElequentArray());
    }
}
