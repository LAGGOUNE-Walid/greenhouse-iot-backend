<?php

namespace App\DataTransferObject;

use App\Contracts\MqttMessage;
use App\Enums\MeasurementType;

class MqttAirMessage implements MqttMessage
{
    public function __construct(
        public int $nodeId,
        public float $temperature,
        public float $humidity,
        public float $pressure
    ) {}

    public function toElequentArray(): array
    {
        return [
            [
                'node_id' => $this->nodeId,
                'measurement_type' => MeasurementType::temperature,
                'value' => $this->temperature,
            ],
            [
                'node_id' => $this->nodeId,
                'measurement_type' => MeasurementType::humidity,
                'value' => $this->humidity,
            ],
            [
                'node_id' => $this->nodeId,
                'measurement_type' => MeasurementType::pressure,
                'value' => $this->pressure,
            ],
        ];
    }
}
