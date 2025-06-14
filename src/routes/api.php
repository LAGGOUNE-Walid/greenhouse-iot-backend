<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NodeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\BatteryController;
use App\Http\Controllers\MeasurementController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('measurements', MeasurementController::class);
Route::resource('nodes', NodeController::class);
Route::resource('batteries', BatteryController::class);

Route::get('measurements-export', [MeasurementController::class, 'export']);
Route::get('measurements-table', [MeasurementController::class, 'all']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/login', function (Request $request) {
    if (! Auth::attempt($request->only('email', 'password'))) {
        return response(['message' => __('auth.failed')], 422);
    }
    $token = auth()->user()->createToken('client');

    return ['token' => $token->plainTextToken];
});
Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();

    return response()->noContent();
});
Route::post('image', [ImageController::class, 'create']);
Route::get('images', [ImageController::class, 'get']);
