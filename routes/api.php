<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FireHydrantController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\FireStationController;
use App\Http\Controllers\ReportController;

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
});

// Your API routes should be here:
Route::get('/fire_hydrants', [FireHydrantController::class, 'index']);
Route::get('/fire-data', [ApiController::class, 'getFireData']); // Assuming ApiController is also in Api namespace or adjust
Route::get('/fire_stations', [FireStationController::class, 'index']);
Route::post('/process-report', [ReportController::class, 'process']);