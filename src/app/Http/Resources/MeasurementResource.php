<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeasurementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (! property_exists($this, "id")) {
            return [
                'id' => null,
                'value' => null,
                'measurement_type' => null,
                'measurement_type_string' => null,
                'created_at' => null,
            ];
        }
        return [
            'id' => $this->id,
            'value' => $this->value,
            'measurement_type' => $this->measurement_type,
            'measurement_type_string' => $this->measurement_type->name,
            'created_at' => $this->created_at->format("H:i:s")
        ];
    }
}
