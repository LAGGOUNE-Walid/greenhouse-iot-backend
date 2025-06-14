<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Enums\MeasurementType;
use Illuminate\Http\Resources\Json\JsonResource;

class MeasurementResource extends JsonResource
{
    public $index;

    public function __construct($resource, $index = null)
    {
        parent::__construct($resource);
        $this->index = $index;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($request->is("api/measurements-table*")) {
            $node = $this->node_id;
        }else {
            $node = $this->node_id.(($this->measurement_type == MeasurementType::soil_moisture) ? " | Sensor ".$this->index + 1 : '');
        }
        return [
            'id' => $this->id ?? null,
            'value' => $this->value ?? null,
            'node_id' => $node,
            'measurement_type' => $this->measurement_type ?? null,
            'measurement_type_string' => $this->measurement_type->name ?? null,
            'created_at' => isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
