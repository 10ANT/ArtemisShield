<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FireHydrantController; // Ensure this is imported
use App\Http\Controllers\ApiController; // Ensure this is imported if you use it for /fire-data

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