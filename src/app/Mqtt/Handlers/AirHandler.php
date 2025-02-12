<?php

namespace App\Mqtt\Handlers;

use App\Models\Measurement;
use App\Enums\MeasurementType;
use App\Contracts\TopicHandlerInterface;

class AirHandler implements TopicHandlerInterface 
{
    public function save(array $message): Measurement {
        dump("AIR message");
        dump($message);
        return Measurement::create([
            'node_id' => 1,
            'measurement_type' => MeasurementType::soil_moisture
        ]);
    }
}
