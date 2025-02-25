<?php

namespace App\Enums;

enum MeasurementType: int
{
    case soil_moisture = 1;
    case temperature = 2;
    case humidity = 3;
    case pressure = 4;
}
