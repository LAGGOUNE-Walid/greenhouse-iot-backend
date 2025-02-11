<?php

namespace App\Mqtt\Factory;

use App\Contracts\TopicHandlerInterface;
use App\Models\Measurement;

abstract class MqttEventHandlerFactory
{
    abstract public function getHandler(): TopicHandlerInterface;
    
    public function handle(array $message): Measurement
    {
        return $this->getHandler()->save($message);
    }

}
