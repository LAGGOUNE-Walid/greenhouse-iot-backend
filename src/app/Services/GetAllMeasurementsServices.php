<?php

namespace App\Services;

use App\Models\Measurement;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

class GetAllMeasurementsServices
{
    public function get(int $intervalDays = 0): Collection
    {
        $query = Measurement::query();

        return $query->get()
            ->groupBy(fn ($item) => $item->created_at->format('Y-m-d'))
            ->map(function ($measurementsByDate) {
                return $measurementsByDate
                    ->groupBy('measurement_type')
                    ->map(function ($items) {
                        return [
                            'measurement_type_string' => $items->first()->measurement_type->name,
                            'avg_measuremens' => round($items->avg('value'), 2),
                        ];
                    })
                    ->values(); // convert to indexed array
            });
    }
}
