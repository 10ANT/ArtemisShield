<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WildfireOfficerController;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\FireHydrantController;
use App\Http\Controllers\FirefighterController;
use App\Models\FireHydrant;

Route::get('/', function () {
    return view('main');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});




//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Wildfire Officer Routes
Route::middleware(['auth', 'role:Wildfire Management Officer'])->group(function () {
    Route::get('/officer-dashboard', [WildfireOfficerController::class, 'dashboard'])->name('officer.dashboard');
    Route::get('/api/dashboard-data', [WildfireOfficerController::class, 'getDashboardData']);

    Route::get('/firefighter-dashboard', [FirefighterController::class, 'dashboard'])->name('firefighter.dashboard');
});







Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
