<?php

namespace Database\Factories;

use App\Enums\NodeType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Node>
 */
class NodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = [NodeType::SOIL_NODE, NodeType::AIR_NODE];

        return [
            'id' => rand(1, 9999),
            'type' => $types[array_rand($types)],
        ];
    }
}
