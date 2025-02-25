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

        return [
            'measurement_type' => $types[array_rand($types)],
            'value' => rand(0, 1000),
        ];
    }
}
