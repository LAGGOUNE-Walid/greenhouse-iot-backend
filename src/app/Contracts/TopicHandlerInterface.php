<?php

namespace App\Contracts;

interface TopicHandlerInterface
{
    public function save(MqttMessage $message): bool;
}
