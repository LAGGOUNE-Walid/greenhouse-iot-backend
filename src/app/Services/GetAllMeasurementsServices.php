<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use App\Models\Measurement;

class GetAllMeasurementsServices
{
    public function get(?string $date = null): array
    {
        $query = Measurement::orderBy('created_at');

        if ($date) {
            $date = Carbon::parse($date);
            $query->whereDate('created_at', $date);
        }

        $rawData = $query->get();

        $grouped = $rawData->groupBy('measurement_type');
        $result = [];
        foreach ($grouped as $typeId => $items) {

            $typeName = $items->first()->measurement_type->name;

            $hourlyData = [];
            $itemsTimestamp = $items->groupBy(function ($item) {
                return $item->created_at->getTimestamp() * 1000;
            });
            foreach ($itemsTimestamp as $timestamp => $items) {
                $hourlyData[$timestamp] = round($items->avg("value"));
            }
            $result[$typeName] = array_map(function ($timestamp, $value) {
                return ['x' => $timestamp, 'y' => $value];
            }, array_keys($hourlyData), array_values($hourlyData));
        }
        return $result;
    }
}
