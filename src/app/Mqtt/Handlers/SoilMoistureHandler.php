<?php

namespace App\Mqtt\Handlers;

use App\Models\Measurement;
use App\Enums\MeasurementType;
use App\Contracts\TopicHandlerInterface;

class SoilMoistureHandler implements TopicHandlerInterface
{
    public function save(array $message): array {
        dump("SOIL MESSAGE");
        dump($message);
        return [
            Measurement::create(['node_id' => $message['node_id'], 'measurement_type' => MeasurementType::soil_moisture, 'value' => $message['soil1']]),
            Measurement::create(['node_id' => $message['node_id'], 'measurement_type' => MeasurementType::soil_moisture, 'value' => $message['soil2']]),
            Measurement::create(['node_id' => $message['node_id'], 'measurement_type' => MeasurementType::soil_moisture, 'value' => $message['soil3']]),
            Measurement::create(['node_id' => $message['node_id'], 'measurement_type' => MeasurementType::soil_moisture, 'value' => $message['soil4']]),
            Measurement::create(['node_id' => $message['node_id'], 'measurement_type' => MeasurementType::soil_moisture, 'value' => $message['soil5']])
        ];
    }
}
