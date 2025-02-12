<?php

namespace App\Mqtt\Handlers;

use App\Models\Measurement;
use App\Enums\MeasurementType;
use App\Contracts\TopicHandlerInterface;

class AirHandler implements TopicHandlerInterface
{
    public function save(array $message): array
    {
        return [
            Measurement::create([
                'node_id' => $message['node_id'],
                'measurement_type' => MeasurementType::temperature,
                'value' => $message['temperature']
            ]),
            Measurement::create([
                'node_id' => $message['node_id'],
                'measurement_type' => MeasurementType::humidity,
                'value' => $message['humidity']
            ]),
            Measurement::create([
                'node_id' => $message['node_id'],
                'measurement_type' => MeasurementType::pressure,
                'value' => $message['pressure']
            ]),
        ];
    }
}
