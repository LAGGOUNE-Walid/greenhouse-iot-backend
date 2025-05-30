<?php

namespace App\Services;

use Carbon\CarbonPeriod;
use App\Models\Measurement;
use Illuminate\Http\Request;
use App\Enums\MeasurementType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\MeasurementResource;

class GetMeasurementOfDayService
{
    public function get(Request $request): Collection
    {
        if ($request->has('interval') and (int) $request->interval <= 31 and (int) $request->interval > 0) {
            return $this->getByInterval((int) $request->interval);
        }
        if ((int) $request->interval == -1) {
            return $this->getLastMeasurements();
        }

        return $this->getTodayView();
    }

    public function getByInterval(int $interval): Collection
    {
        $measurements = Measurement::whereDate('created_at', '>=', now()->subDays($interval - 1))
            ->selectRaw('ROUND(AVG(value)) as avg_measuremens, strftime("%d", created_at) as created_day, measurement_type')
            ->groupBy('measurement_type', 'created_day')
            ->get();
        $dates = CarbonPeriod::create(now()->subDays($interval - 1), '1 day', now());
        $datesMeasurements = [];
        foreach ($dates as $date) {
            $datesMeasurements[$date->format('Y-m-d')] = $measurements->where('created_day', $date->format('d'))->map(function (Measurement $m) {
                $m->measurement_type_string = $m->measurement_type->name;

                return $m;
            });
        }

        return collect(($datesMeasurements));
    }

    public function getTodayView(): Collection
    {
        $todayMeasurements = Measurement::whereDate('created_at', '=', now())
            ->selectRaw('ROUND(AVG(value)) as avg_measuremens, strftime("%H", created_at) as created_hour, measurement_type')
            ->groupBy('measurement_type', 'created_hour')
            ->get();
        $dayHours = [];
        for ($i = 0; $i <= now()->format("H"); $i++) {
            $hour = $i;
            if ($i < 10) {
                $hour = "0$hour";
            }
            $dayHours[$hour . 'h'] = $todayMeasurements->where('created_hour', $hour)->map(function (Measurement $m) {
                $m->measurement_type_string = $m->measurement_type->name;
                return $m;
            });
        }
        return collect($dayHours);
    }

    public function getLastMeasurements(): Collection
    {
        $measurements = collect([]);

        $latestSoil = Measurement::orderBy('created_at', 'desc')
            ->where('measurement_type', MeasurementType::soil_moisture)
            ->limit(5)
            ->get();

        $measurements->put(MeasurementType::soil_moisture->name, MeasurementResource::collection($latestSoil));

        $lastTemperature = Measurement::where('measurement_type', MeasurementType::temperature)->latest('created_at')->first();
        $measurements->put(MeasurementType::temperature->name, new MeasurementResource($lastTemperature));

        $lastHumidity = Measurement::where('measurement_type', MeasurementType::humidity)->latest('created_at')->first();
        $measurements->put(MeasurementType::humidity->name, new MeasurementResource($lastHumidity));

        $lastPressure = Measurement::where('measurement_type', MeasurementType::pressure)->latest('created_at')->first();
        $measurements->put(MeasurementType::pressure->name, new MeasurementResource($lastPressure));

        return $measurements;
    }
}
