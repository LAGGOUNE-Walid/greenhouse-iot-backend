<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use App\Models\Measurement;

class GetAllMeasurementsServices
{
    public function get(?string $date = null): array
    {
        $timezone = 'Africa/Algiers'; // or whatever your local time is
        $query = Measurement::orderBy('created_at');

        if ($date) {
            $date = Carbon::parse($date)->timezone($timezone);
            $query->whereDate('created_at', $date);
        }

        $rawData = $query->get();

        // Group by measurement type
        $grouped = $rawData->groupBy('measurement_type');

        $result = [];

        foreach ($grouped as $typeId => $items) {
            $typeName = $items->first()->measurement_type->name;

            // Group by exact timestamp
            $byTimestamp = [];

            foreach ($items as $item) {
                $timestamp = $item->created_at->timezone($timezone)->getTimestamp() * 1000;

                if (!isset($byTimestamp[$timestamp])) {
                    $byTimestamp[$timestamp] = ['sum' => 0, 'count' => 0];
                }

                $byTimestamp[$timestamp]['sum'] += $item->value;
                $byTimestamp[$timestamp]['count']++;
            }

            // Average per timestamp
            $result[$typeName] = [];

            foreach ($byTimestamp as $timestamp => $data) {
                $avg = round($data['sum'] / $data['count'], 2);
                $result[$typeName][] = ['x' => (int)$timestamp, 'y' => $avg];
            }

            // Sort by x
            usort($result[$typeName], fn($a, $b) => $a['x'] <=> $b['x']);
        }

        return $result;
    }
}
