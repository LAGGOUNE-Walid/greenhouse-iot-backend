<?php

namespace App\DataTransferObject;

use App\Contracts\MqttMessage;
use App\Enums\MeasurementType;

class MqttSoilMessage implements MqttMessage
{
    public function __construct(public int $nodeId, public array $soilMeasurements) {}

    public function toElequentArray(): array
    {
        $elequentArray = [];
        foreach ($this->soilMeasurements as $value) {
            $elequentArray[] = [
                'node_id' => $this->nodeId,
                'value' => $value,
                'measurement_type' => MeasurementType::soil_moisture,
                'created_at' => now(),
            ];
        }

        return $elequentArray;
    }
}
