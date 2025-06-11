<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RouteController; 
use App\Http\Controllers\Api\WildfirePerimeterController; 
use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\V1\ProxyController; 
use App\Http\Controllers\Api\V1\WindController;

use App\Http\Controllers\Api\FireDataController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::get('/fire-data', [FireDataController::class, 'getFireData']);
    Route::get('/weather-data', [FireDataController::class, 'getWeatherData']);
});

Route::apiResource('routes', RouteController::class)->only(['index', 'store', 'destroy']);


// this route for fetching official wildfire perimeters
Route::get('/wildfire-perimeters', [WildfirePerimeterController::class, 'index']);

Route::get('/weather-for-point', [WeatherController::class, 'getWeatherForPoint']);

Route::get('/v1/wind-data-proxy', [ProxyController::class, 'getWindData']);

Route::get('/v1/gfs-wind-data', [WindController::class, 'getGfsData']);

