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

            // Create hourly buckets for the selected day
            $hourlyData = [];

            if ($date) {
                // Initialize hourly buckets (0-23) with null values
                for ($hour = 0; $hour < 24; $hour++) {
                    $hourTimestamp = $date->copy()->setHour($hour)->startOfHour();
                    $hourlyData[$hourTimestamp->getTimestamp() * 1000] = null;
                }

                // Fill in actual measurements
                foreach ($items as $item) {
                    $hour = $item->created_at->hour;
                    $hourTimestamp = $item->created_at->copy()->setHour($hour)->startOfHour();
                    $timestamp = $hourTimestamp->getTimestamp() * 1000;

                    // Calculate average for this hour if multiple measurements exist
                    if (!isset($hourlyData[$timestamp])) {
                        $hourlyData[$timestamp] = 0;
                        $count = 0;
                    }

                    $hourlyData[$timestamp] += $item->value;
                    $count++;
                    $hourlyData[$timestamp] = round($hourlyData[$timestamp] / $count, 2);
                }

                // Convert to array of points
                $result[$typeName] = array_map(function ($timestamp, $value) {
                    return ['x' => $timestamp, 'y' => $value];
                }, array_keys($hourlyData), array_values($hourlyData));
            } else {
                // Original behavior for all data
                $result[$typeName] = collect($items)
                    ->groupBy(function ($item) {
                        // Group by exact timestamp in milliseconds
                        return $item->created_at->getTimestamp() * 1000;
                    })
                    ->map(function ($group, $timestamp) {
                        $avg = round($group->avg('value'), 2);
                        return ['x' => $timestamp, 'y' => $avg];
                    })
                    ->sortBy('x') // optional, to keep the array ordered by time
                    ->values()
                    ->toArray();
            }
        }

        return $result;
    }
}
