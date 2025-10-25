<?php

namespace App\Http\Controllers;

use App\Http\Resources\BatteryLevelCollection;
use App\Models\BatteryLevel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BatteryController extends Controller
{
    public function index(Request $request): BatteryLevelCollection|array
    {
        if ($request->query("chart")) {
            return $this->getBatteriesChart();
        }
        return new BatteryLevelCollection(BatteryLevel::whereDate("created_at", ">=", now()->subMonth())->get());
    }

    public function getBatteriesChart(): array
    {
        $query = BatteryLevel::orderBy('created_at');

        $rawData = $query->get();

        $grouped = $rawData->groupBy('node_id');
        $result = [];
        foreach ($grouped as $nodeId => $items) {

            $hourlyData = [];
            $itemsTimestamp = $items->groupBy(function ($item) {
                return $item->created_at->getTimestamp() * 1000;
            });
            foreach ($itemsTimestamp as $timestamp => $items) {
                $hourlyData[$timestamp] = round($items->avg("value"));
            }
            $result[$nodeId] = array_map(function ($timestamp, $value) {
                return ['x' => $timestamp, 'y' => $value];
            }, array_keys($hourlyData), array_values($hourlyData));
        }
        return $result;
    }
}
