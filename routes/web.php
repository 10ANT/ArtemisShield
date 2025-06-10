<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WildfireOfficerController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\WildfireOfficer\DashboardController;

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
    
// Wildfire Officer Dashboard
Route::get('/wildfire-officer/dashboard', [DashboardController::class, 'index'])->name('wildfire-officer.dashboard');

});






Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/debug-csv', function() {
    $url = "https://firms.modaps.eosdis.nasa.gov/api/area/csv/dd346dedf27002dca8da861050193242/VIIRS_SNPP_NRT/world/1/2025-06-09";
    
    $response = Http::get($url);
    $csvData = $response->body();
    $lines = explode("\n", trim($csvData));
    
    return response()->json([
        'total_lines' => count($lines),
        'header' => $lines[0] ?? 'No header',
        'first_data_line' => $lines[1] ?? 'No data line',
        'second_data_line' => $lines[2] ?? 'No second line',
        'header_columns' => str_getcsv($lines[0] ?? ''),
        'first_data_columns' => str_getcsv($lines[1] ?? ''),
        'column_count' => count(str_getcsv($lines[1] ?? ''))
    ]);
});
