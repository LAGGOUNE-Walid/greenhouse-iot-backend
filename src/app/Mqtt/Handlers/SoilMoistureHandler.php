<?php

namespace App\Mqtt\Handlers;

use App\Models\Measurement;
use App\Contracts\TopicHandlerInterface;

class SoilMoistureHandler implements TopicHandlerInterface
{
    public function save(array $message): Measurement {
        dump("SOIL MESSAGE");
        dump($message);
        return Measurement::create([]);
    }
}
