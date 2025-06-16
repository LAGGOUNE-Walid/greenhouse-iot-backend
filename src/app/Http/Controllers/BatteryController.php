<?php

namespace App\Http\Controllers;

use App\Http\Resources\BatteryLevelCollection;
use App\Models\BatteryLevel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BatteryController extends Controller
{
    public function index(Request $request): BatteryLevelCollection
    {
        return new BatteryLevelCollection(BatteryLevel::whereDate("created_at", ">=", now()->subMonth())->get());
    }
}
