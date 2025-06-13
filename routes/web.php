<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WildfireOfficerController;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\FireHydrantController;
use App\Http\Controllers\FirefighterController;
use App\Models\FireHydrant;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\WildfireOfficer\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\FireIncidentController;

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
    Route::get('/officer-dashboard', [DashboardController::class, 'dashboard'])->name('officer.dashboard');
    Route::get('/api/dashboard-data', [DashboardController::class, 'getDashboardData']);

    Route::get('/firefighter-dashboard', [FirefighterController::class, 'dashboard'])->name('firefighter.dashboard');
    Route::get('/reports/history', [ReportController::class, 'history'])->middleware('auth');
});

    
// Wildfire Officer Dashboard
Route::get('/wildfire-officer/dashboard', [DashboardController::class, 'index'])->name('wildfire-officer.dashboard');



Route::get('/', function () {
    return view('main');
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


Route::get('/wildfire-officer/wind-global.json', function () {
    $filePath = storage_path('app/public/wind-global.json');
    
    if (!file_exists($filePath)) {
        return response()->json(['error' => 'File not found'], 404);
    }
    
    return response()->json(json_decode(file_get_contents($filePath), true));
});


Route::middleware('auth:sanctum')->prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::post('/mark-as-read', [NotificationController::class, 'markAsRead']);
});

Route::post('/process-report', [ReportController::class, 'process'])->middleware('auth:sanctum');


// Replace your old route for the dashboard with this one
Route::get('/firefighter-dashboard', [FireIncidentController::class, 'dashboard'])->name('firefighter.dashboard');