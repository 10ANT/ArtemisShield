<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\Api\FireHydrantController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\FireStationController;
use App\Http\Controllers\ReportController;
=======
use App\Http\Controllers\Api\RouteController; 
use App\Http\Controllers\Api\WildfirePerimeterController; 
use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\V1\ProxyController; 
use App\Http\Controllers\Api\V1\WindController;

use App\Http\Controllers\Api\FireDataController;
>>>>>>> 78e0093122e852649f764ea125f3e0e58aa97151

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
<<<<<<< HEAD
});

// Your API routes should be here:
Route::get('/fire_hydrants', [FireHydrantController::class, 'index']);
Route::get('/fire-data', [ApiController::class, 'getFireData']); // Assuming ApiController is also in Api namespace or adjust
Route::get('/fire_stations', [FireStationController::class, 'index']);
Route::post('/process-report', [ReportController::class, 'process']);
=======
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

>>>>>>> 78e0093122e852649f764ea125f3e0e58aa97151
