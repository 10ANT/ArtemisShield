<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WildfireOfficerController;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\FireHydrantController;
use App\Http\Controllers\FirefighterController;
use App\Models\FireHydrant;

use App\Http\Controllers\HistoricalMapController;



use Illuminate\Support\Facades\Http;
use App\Http\Controllers\WildfireOfficer\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ParamedicsController;
use App\Http\Controllers\PredictionController;

use App\Http\Controllers\FireIncidentController;


use App\Http\Controllers\AgentController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TranscriptionController;

//new controller for end user and alerting
use App\Http\Controllers\WildfireOfficer\StatusUpdatesController;
use App\Http\Controllers\EndUser\DashboardController as EndUserDashboardController;
use App\Http\Controllers\EndUser\AgentController as EndUserAgentController;
use App\Http\Controllers\Api\AlertController;
use App\Http\Controllers\Api\StatusUpdateController;
use App\Http\Controllers\ProxyController;

use App\Http\Controllers\FirstResponderDashboardController;


// Proxy route for NOAA images
Route::get('/proxy/noaa/{path}', [ProxyController::class, 'getNoaaImage'])->where('path', '.*');

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

    // END USER DASHBOARD
    Route::prefix('end-user')->name('end-user.')->middleware('auth')->group(function () {
        Route::get('/dashboard', [EndUserDashboardController::class, 'index'])->name('dashboard');
        Route::post('/agent/chat', [EndUserAgentController::class, 'chat'])->name('agent.chat');
        Route::post('/agent/reset', [EndUserAgentController::class, 'reset'])->name('agent.reset');
    });

    // API-like routes using web authentication
    Route::prefix('api')->middleware('auth')->group(function () {
        // Status Updates (from end-user)
        Route::post('/status-updates', [StatusUpdateController::class, 'store'])->name('api.status-updates.store');
        
        // Alerts (for officers)
        Route::get('/alerts', [AlertController::class, 'index'])->name('api.alerts.index');
        Route::post('/alerts', [AlertController::class, 'store'])->name('api.alerts.store')->middleware('role:Wildfire Management Officer');
        Route::delete('/alerts/{alert}', [AlertController::class, 'destroy'])->name('api.alerts.destroy')->middleware('role:Wildfire Management Officer');
    });

});




//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Wildfire Officer Routes

Route::middleware(['auth', 'role:Wildfire Management Officer'])->group(function () {
    Route::get('/officer-dashboard', [DashboardController::class, 'dashboard'])->name('officer.dashboard');
    Route::get('/api/dashboard-data', [DashboardController::class, 'getDashboardData']);

    Route::get('/firefighter-dashboard', [FirefighterController::class, 'dashboard'])->name('firefighter.dashboard');
    Route::get('/reports/history', [ReportController::class, 'history'])->middleware('auth');

    Route::get('/responder-dashboard',  [ParamedicsController::class, 'dashboard'])->name('rescue.dashboard');
    Route::get('/analyst-dashboard', [PredictionController::class, 'dashboard'])->name('prediction.dashboard');
    Route::get('analyst-wildfire-risk', [PredictionController::class, 'wildfireRisk'])->name('prediction.wildfire-risk');
    // In routes/web.php
});

// TEMPORARY DEBUGGING ROUTE
Route::get('/debug-config', function () {
    dd(config('services.azure'));
});

Route::middleware(['auth', 'role:Wildfire Management Officer'])->prefix('wildfire-officer')->name('wildfire-officer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/status-updates', [StatusUpdatesController::class, 'index'])->name('status-updates');
        
// Wildfire Officer Dashboard
Route::get('/wildfire-officer/dashboard', [DashboardController::class, 'dashboard'])->name('wildfire-officer.dashboard');


});




Route::get('/', function () {
    return view('main');
});



Route::get('/terms-of-service', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/faq', [PageController::class, 'faq'])->name('pages.faq');


Route::middleware('auth:sanctum')->prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::post('/mark-as-read', [NotificationController::class, 'markAsRead']);
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





Route::middleware(['auth', 'role:Wildfire Management Officer,Firefighter'])->prefix('wildfire-officer')->name('wildfire-officer.')->group(function () {
 
// Replace your old route for the dashboard with this one


});
Route::get('/firefighter-dashboard', [FireIncidentController::class, 'dashboard'])->name('firefighter.dashboard');
Route::post('/agent/chat', [AgentController::class, 'chat']);
Route::post('/agent/submit-tool-output', [AgentController::class, 'submitToolOutput']);
Route::post('/agent/reset', [AgentController::class, 'reset']);



Route::post('/transcribe/audio', [TranscriptionController::class, 'transcribe'])->name('transcription.transcribe');



// Routes for the Historical Fire Map
Route::get('/historical-map', [HistoricalMapController::class, 'showMap'])->name('historical.map');
Route::get('/api/historical-fires', [HistoricalMapController::class, 'getFireData'])->name('api.historical.fires');
Route::middleware(['auth:sanctum', 'verified', 'role:Ambulance Staff'])->prefix('first-responder')->group(function () {
    Route::get('/dashboard', [FirstResponderDashboardController::class, 'index'])->name('first-responder.dashboard');
});
