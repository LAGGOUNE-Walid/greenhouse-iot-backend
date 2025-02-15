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
        if (! $this) {
            return [];
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
