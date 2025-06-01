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

        // Group by measurement type
        $grouped = $rawData->groupBy('measurement_type');

        $result = [];

        foreach ($grouped as $typeId => $items) {
            $typeName = $items->first()->measurement_type->name;

            // Group by exact timestamp (milliseconds)
            $byTimestamp = [];

            foreach ($items as $item) {
                $timestamp = $item->created_at->getTimestamp() * 1000;

                if (!isset($byTimestamp[$timestamp])) {
                    $byTimestamp[$timestamp] = ['sum' => 0, 'count' => 0];
                }

                $byTimestamp[$timestamp]['sum'] += $item->value;
                $byTimestamp[$timestamp]['count']++;
            }

            // Now calculate average for each timestamp
            $result[$typeName] = [];

            foreach ($byTimestamp as $timestamp => $data) {
                $average = round($data['sum'] / $data['count'], 2);
                $result[$typeName][] = ['x' => (int)$timestamp, 'y' => $average];
            }

            // Optional: sort by timestamp if needed
            usort($result[$typeName], fn($a, $b) => $a['x'] <=> $b['x']);
        }

        return $result;
    }
}
