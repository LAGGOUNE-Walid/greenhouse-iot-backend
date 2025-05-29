<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use App\Models\Measurement;

class GetAllMeasurementsServices
{
    public function get(): array
    {
        // Get all measurements ordered by creation time
        $rawData = Measurement::orderBy('created_at')->get();

        // Group by measurement type and map to {x: timestamp_ms, y: value}
        $grouped = $rawData->groupBy('measurement_type');

        $result = [];
        foreach ($grouped as $typeId => $items) {
            $typeName = $items->first()->measurement_type->name;
            $result[$typeName] = $items->map(function ($item) {
                return [
                    'x' => $item->created_at->getTimestamp() * 1000, // JS timestamps in ms
                    'y' => round($item->value, 2),
                ];
            })->toArray();
        }

        return $result;
    }
}
