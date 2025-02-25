<?php

use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NodeController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\MeasurementController;
use Nette\Utils\ImageColor;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('measurements', MeasurementController::class);
Route::resource('nodes', NodeController::class);

Route::get('measurements-export', [MeasurementController::class, 'export']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/login', function (Request $request) {
    if (!Auth::attempt($request->only('email', 'password'))) {
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
// Route::post("image", function (Request $request) {
    
// });

