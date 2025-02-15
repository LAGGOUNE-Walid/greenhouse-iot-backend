<?php

namespace App\Contracts;

interface MqttMessage
{
    public function toElequentArray(): array;
}
