<?php

namespace App\DataTransferObject;

use App\Contracts\MqttMessage;

class MqttBatteryMessage implements MqttMessage
{
    public function __construct(public int $nodeId, public float $batteryLevel) {}

    public function toElequentArray(): array
    {
        return [
            'node_id' => $this->nodeId,
            'value' => $this->batteryLevel,
        ];
    }
}
