<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wildfire;
use GuzzleHttp\Client;

class WildfireOfficerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Wildfire Management Officer']);
    }

    public function dashboard()
    {
        return view('wildfire-officer.dashboard');
    }

    public function getDashboardData()
    {
        return response()->json([
            'active_fires' => $this->getActiveFires(),
            'weather' => $this->getWeatherData(),
            'live_stats' => $this->getLiveStats(),
            'recent_reports' => $this->getRecentReports(),
        ]);
    }

    private function getActiveFires()
    {
        // Combine database fires with NASA FIRMS data
        $dbFires = Wildfire::where('status', 'active')->get();
        $nasaFires = $this->getNASAFirmsData();
        
        return [
            'database_fires' => $dbFires,
            'nasa_fires' => $nasaFires
        ];
    }

    private function getNASAFirmsData()
    {
        try {
            $client = new Client();
            $response = $client->get('https://firms.modaps.eosdis.nasa.gov/api/country/csv/your-api-key/VIIRS_SNPP_NRT/USA/1');
            
            $data = $response->getBody()->getContents();
            $lines = explode("\n", $data);
            $fires = [];
            
            foreach (array_slice($lines, 1) as $line) {
                if (empty($line)) continue;
                $fields = str_getcsv($line);
                if (count($fields) >= 5) {
                    $fires[] = [
                        'latitude' => $fields[0],
                        'longitude' => $fields[1],
                        'brightness' => $fields[2],
                        'confidence' => $fields[8] ?? 'n',
                        'acq_date' => $fields[5],
                        'acq_time' => $fields[6],
                    ];
                }
            }
            
            return $fires;
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getWeatherData()
    {
        try {
            $client = new Client();
            $response = $client->get('https://api.openweathermap.org/data/2.5/weather', [
                'query' => [
                    'lat' => 34.0522, // Default to LA
                    'lon' => -118.2437,
                    'appid' => env('OPENWEATHER_API_KEY'),
                    'units' => 'metric'
                ]
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return [
                'main' => ['temp' => 28, 'humidity' => 45],
                'wind' => ['speed' => 15, 'deg' => 270]
            ];
        }
    }

    private function getLiveStats()
    {
        return [
            'active_fires' => 12,
            'resources_deployed' => 45,
            'properties_at_risk' => 234,
            'water_sources' => 8
        ];
    }

    private function getRecentReports()
    {
        return [
            [
                'title' => 'Fire Containment',
                'location' => 'North Ridge Area',
                'time' => '10 min ago',
                'status' => 'success'
            ]
        ];
    }
}