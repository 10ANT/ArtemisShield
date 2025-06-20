<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FireHydrantController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\FireStationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Api\RouteController; 
use App\Http\Controllers\Api\WildfirePerimeterController; 
use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\V1\ProxyController; 
use App\Http\Controllers\Api\V1\WindController;
use App\Http\Controllers\Api\FireDataController;


use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Api\RoutingController;

use App\Http\Controllers\FireIncidentController;
use App\Http\Controllers\Api\ChatController;


use App\Http\Controllers\GeocodingController; 
use App\Http\Controllers\Api\UserController;





use App\Http\Controllers\Api\HospitalController;
// use App\Http\Controllers\Api\AedLocationController;
use app\Http\Controllers\Api\AedLocationController;
use App\Http\Controllers\Api\MedicalIncidentController;
use App\Http\Controllers\Api\WildfireRiskController;
use App\Http\Controllers\Api\AmbeeController;



use App\Http\Controllers\Api\StatusUpdateController;
use App\Http\Controllers\WildfirePredictionController;


Route::get('/status-updates/poll', [StatusUpdateController::class, 'poll']);


Route::patch('/status-updates/{statusUpdate}/fulfill', [StatusUpdateController::class, 'fulfill']);

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


})->middleware('auth:sanctum');





// Your API routes should be here:
Route::get('/fire_hydrants', [FireHydrantController::class, 'index']);
Route::get('/fire-data', [ApiController::class, 'getFireData']); // Assuming ApiController is also in Api namespace or adjust
Route::get('/fire_stations', [FireStationController::class, 'index']);
Route::post('/process-report', [ReportController::class, 'process']);



Route::prefix('v1')->group(function () {
    Route::get('/fire-data', [FireDataController::class, 'getFireData']);
    Route::get('/weather-data', [FireDataController::class, 'getWeatherData']);
});

Route::apiResource('routes', RouteController::class)->only(['index', 'store', 'destroy']);


// this route for fetching official wildfire perimeters
Route::get('/wildfire-perimeters', [WildfirePerimeterController::class, 'index']);

Route::get('/weather-for-point', [WeatherController::class, 'getWeatherForPoint']);

//Route::get('/v1/wind-data-proxy', [ProxyController::class, 'getWindData']);

Route::get('/v1/gfs-wind-data', [WindController::class, 'getGfsData']);




Route::get('/routing/find-nearest-station', [RoutingController::class, 'getRouteToNearestStation']);



// Add this line
Route::get('/fire-incidents', [FireIncidentController::class, 'getApiIncidents']);

Route::post('/chat', [ChatController::class, 'sendMessage']);

Route::get('/geocode', [GeocodingController::class, 'geocode']); // Add this line



Route::get('/geocode', [GeocodingController::class, 'geocode']); // Add this line



// Route for all hospitals (as requested in the previous step)
Route::get('/hospitals', [HospitalController::class, 'index']);

// Route for AED locations (handles the dynamic bbox query)
// Route for AED locations (handles the dynamic bbox query)
// Make sure to update the namespace if the controller is not under Api
// Route::get('/aed-locations', [AedLocationController::class, 'index']);

// Route for medical incidents
Route::get('/medical-incidents', [MedicalIncidentController::class, 'index']);


Route::get('/wildfire-risk/point-data', [WildfireRiskController::class, 'getPointData']);

// Ambee Fire Data Proxy
Route::get('/ambee/fire-data', [AmbeeController::class, 'getFireDataByLatLng']);
Route::get('/ambee/fire-risk', [AmbeeController::class, 'getFireRiskDataByLatLng']);



Route::post('/ambee/classify-image', [AmbeeController::class, 'classifyImage']); // NEW ROUTE
// this new route for AI intensity prediction
Route::post('/predict-intensity', [WildfirePredictionController::class, 'predictIntensity'])->name('api.predict.intensity');


// NEW route for classification-based spread prediction
Route::post('/predict-spread', [App\Http\Controllers\WildfirePredictionController::class, 'predictSpread'])->name('api.predict.spread');

Route::delete('/reports/{report}', [ReportController::class, 'destroy']);