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

        // Group measurements by type
        $grouped = $rawData->groupBy('measurement_type');

        $result = [];

        foreach ($grouped as $typeId => $items) {
            $typeName = $items->first()->measurement_type->name;

            if ($date) {
                // Step 1: Initialize hourly buckets with ['sum' => 0, 'count' => 0]
                $hourlyData = [];
                for ($hour = 0; $hour < 24; $hour++) {
                    $hourTimestamp = $date->copy()->setHour($hour)->startOfHour()->getTimestamp() * 1000;
                    $hourlyData[$hourTimestamp] = ['sum' => 0, 'count' => 0];
                }

                // Step 2: Accumulate sum and count for each hour
                foreach ($items as $item) {
                    $hour = $item->created_at->hour;
                    $hourTimestamp = $item->created_at->copy()->setHour($hour)->startOfHour()->getTimestamp() * 1000;

                    $hourlyData[$hourTimestamp]['sum'] += $item->value;
                    $hourlyData[$hourTimestamp]['count']++;
                }

                // Step 3: Calculate averages
                $averagedData = [];
                foreach ($hourlyData as $timestamp => $data) {
                    $averagedData[$timestamp] = $data['count'] > 0
                        ? round($data['sum'] / $data['count'], 2)
                        : null;
                }

                // Step 4: Convert to array of points
                $result[$typeName] = array_map(function ($timestamp, $value) {
                    return ['x' => $timestamp, 'y' => $value];
                }, array_keys($averagedData), array_values($averagedData));
            } else {
                // No specific date: return raw timestamped data
                $result[$typeName] = $items->map(function ($item) {
                    return [
                        'x' => $item->created_at->getTimestamp() * 1000,
                        'y' => round($item->value, 2),
                    ];
                })->toArray();
            }
        }

        return $result;
    }
}
