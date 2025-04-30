<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'type_name' => $this->type->name,
            'last_battery_level' =>  max(0, min(100, $this->batteryLevels->last())),
            'last_measurement' => $this->measurements->last(),
        ];
    }
}
