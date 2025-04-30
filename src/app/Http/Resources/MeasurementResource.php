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
        return [
            'id' => $this->id ?? null,
            'value' => $this->value ?? null,
            'node_id' => $this->node_id,
            'measurement_type' => $this->measurement_type ?? null,
            'measurement_type_string' => $this->measurement_type->name ?? null,
            'created_at' => isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
