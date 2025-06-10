<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RouteController; 


use App\Http\Controllers\Api\FireDataController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::get('/fire-data', [FireDataController::class, 'getFireData']);
    Route::get('/weather-data', [FireDataController::class, 'getWeatherData']);
});

Route::apiResource('routes', RouteController::class)->only(['index', 'store', 'destroy']);