<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FireDataController extends Controller
{
    private $nasaApiKey;
    private $openWeatherApiKey;

    public function __construct()
    {
        $this->nasaApiKey = config('services.nasa.api_key');
        $this->openWeatherApiKey = config('services.openweather.api_key');
    }

    public function getFireData(Request $request)
    {
        $source = $request->get('source', 'VIIRS_SNPP_NRT');
        $area = $request->get('area', 'world');
        $dayRange = $request->get('day_range', 1);
        $date = $request->get('date', Carbon::yesterday()->format('Y-m-d')); // Use yesterday by default

        // Build the API URL according to NASA FIRMS format
        $url = "https://firms.modaps.eosdis.nasa.gov/api/area/csv/{$this->nasaApiKey}/{$source}/{$area}/{$dayRange}/{$date}";

        try {
            $response = Http::timeout(30)->get($url);
            Log::info('NASA FIRMS API request', ['url' => $url, 'status' => $response->status()]);

            if ($response->successful()) {
                $csvData = $response->body();
                $fires = $this->parseCsvData($csvData, $source);

                // Limit the number of fire detections to prevent large payloads
                $fires = array_slice($fires, 0,1000); // Increased limit for better coverage

                return response()->json([
                    'success' => true,
                    'data' => $fires,
                    'count' => count($fires),
                    'source' => $source,
                    'date' => $date
                ]);
            }

            Log::error('NASA FIRMS API failed', ['status' => $response->status(), 'body' => $response->body()]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch fire data: ' . $response->status(),
                'url' => $url
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Fire data fetch failed', ['error' => $e->getMessage(), 'url' => $url]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch fire data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getWeatherData(Request $request)
    {
        $lat = $request->get('lat', 34.0522);
        $lon = $request->get('lon', -118.2437);

        try {
            $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $this->openWeatherApiKey,
                'units' => 'metric'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'data' => [
                        'temperature' => round($data['main']['temp']),
                        'humidity' => $data['main']['humidity'],
                        'wind_speed' => round($data['wind']['speed'] * 3.6),
                        'wind_direction' => $data['wind']['deg'] ?? 0,
                        'description' => $data['weather'][0]['description'],
                        'fire_risk' => $this->calculateFireRisk($data)
                    ]
                ]);
            }

            Log::error('OpenWeather API failed', ['status' => $response->status(), 'body' => $response->body()]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch weather data'
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Weather data fetch failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch weather data: ' . $e->getMessage()
            ], 500);
        }
    }

   private function parseCsvData($csvData, $source)
{
    $lines = explode("\n", trim($csvData));
    $fires = [];

    if (count($lines) < 2) {
        return $fires; // No data or only header
    }

    // Skip header and parse data
    for ($i = 1; $i < count($lines); $i++) {
        $line = trim($lines[$i]);
        if (empty($line)) continue;

        $data = str_getcsv($line);
        
        // VIIRS data has 14 columns (not 15):
        // latitude,longitude,bright_ti4,scan,track,acq_date,acq_time,satellite,instrument,confidence,version,bright_ti5,frp,daynight
        if (count($data) >= 14) {
            $fires[] = [
                'latitude' => (float) $data[0],
                'longitude' => (float) $data[1],
                'brightness' => (float) $data[2], // bright_ti4
                'scan' => (float) $data[3],
                'track' => (float) $data[4],
                'acq_date' => $data[5],
                'acq_time' => $data[6],
                'satellite' => $data[7],
                'instrument' => $data[8],
                'confidence' => $data[9],
                'version' => $data[10],
                'bright_t31' => (float) $data[11], // bright_ti5
                'frp' => (float) $data[12],
                'daynight' => $data[13],
                'type' => 0, // Not provided in this format
                'source' => $source,
                'confidence_level' => $this->getViirConfidenceLevel($data[9]),
                'intensity_color' => $this->getIntensityColor((float) $data[12])
            ];
        }
    }

    return $fires;
}

    private function isViirsSensor($source)
    {
        return strpos($source, 'VIIRS') !== false;
    }

    private function parseViirConfidence($confidence)
    {
        // VIIRS confidence can be 'low', 'nominal', 'high' or numeric
        if (is_numeric($confidence)) {
            return (int) $confidence;
        }
        
        switch (strtolower($confidence)) {
            case 'low': return 30;
            case 'nominal': return 50;
            case 'high': return 80;
            default: return 50;
        }
    }

 private function getViirConfidenceLevel($confidence)
{
    if (is_numeric($confidence)) {
        $conf = (int) $confidence;
        if ($conf >= 80) return 'high';
        if ($conf >= 50) return 'medium';
        return 'low';
    }
    
    switch (strtolower($confidence)) {
        case 'h': 
        case 'high': 
            return 'high';
        case 'n': 
        case 'nominal': 
            return 'medium';
        case 'l': 
        case 'low': 
            return 'low';
        default: 
            return 'medium';
    }
}
    private function getConfidenceLevel($confidence)
    {
        if ($confidence >= 80) return 'high';
        if ($confidence >= 50) return 'medium';
        return 'low';
    }

    private function getIntensityColor($frp)
    {
        if ($frp >= 100) return '#FF0000'; // Red - Very High
        if ($frp >= 50) return '#FF6600';  // Orange - High
        if ($frp >= 20) return '#FFAA00';  // Yellow - Medium
        return '#FFDD00'; // Light Yellow - Low
    }

    private function calculateFireRisk($weatherData)
    {
        $temp = $weatherData['main']['temp'];
        $humidity = $weatherData['main']['humidity'];
        $windSpeed = $weatherData['wind']['speed'] ?? 0;

        $risk = 0;
        if ($temp > 30) $risk += 2;
        elseif ($temp > 20) $risk += 1;
        if ($humidity < 30) $risk += 2;
        elseif ($humidity < 50) $risk += 1;
        if ($windSpeed > 10) $risk += 2;
        elseif ($windSpeed > 5) $risk += 1;

        if ($risk >= 5) return 'Extreme';
        if ($risk >= 3) return 'High';
        if ($risk >= 2) return 'Medium';
        return 'Low';
    }
}