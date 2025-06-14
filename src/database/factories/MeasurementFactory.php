<?php

namespace Database\Factories;

use App\Enums\MeasurementType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Measurement>
 */
class MeasurementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = [MeasurementType::soil_moisture, MeasurementType::temperature, MeasurementType::humidity, MeasurementType::pressure];
        $type = $types[array_rand($types)];
        if ($type == MeasurementType::soil_moisture OR $type == MeasurementType::humidity) {
            $value = rand(30, 100);
        }elseif($type == MeasurementType::temperature) {
            $value = rand(20, 50);   
        }else {
            $value = rand(900, 1030);
        }
        return [
            'measurement_type' => $type,
            'value' => $value,
        ];
    }
}
