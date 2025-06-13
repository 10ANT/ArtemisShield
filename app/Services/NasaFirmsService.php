<?php

namespace App\Services;

use App\Models\FireIncident;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NasaFirmsService
{
    protected $apiKey;
    protected $baseUrl = 'https://firms.modaps.eosdis.nasa.gov/api/area/csv/';

    public function __construct()
    {
        // It's best practice to store keys in your .env file
        $this->apiKey = config('services.nasa_firms.api_key');
    }

    /**
     * Fetches fire data from the NASA FIRMS API and stores it in the database.
     * Uses MODIS data for the last 24 hours.
     *
     * @return array ['status' => 'success'|'error'|'skipped', 'message' => string, 'count' => int]
     */
    public function fetchAndStoreIncidents()
    {
        if (empty($this->apiKey) || $this->apiKey === 'YOUR_MAP_KEY') {
            Log::error('NASA FIRMS API key is not configured.');
            return ['status' => 'error', 'message' => 'API key not configured.', 'count' => 0];
        }

        // --- Fetch data from API ---
        // URL covers the entire globe for the last 24 hours using MODIS NRT
        $url = "{$this->baseUrl}{$this->apiKey}/MODIS_NRT/-180,-90,180,90/1";

        try {
            $response = Http::timeout(60)->get($url); // Increased timeout for large dataset

            if ($response->failed()) {
                Log::error('NASA FIRMS API request failed.', ['status' => $response->status(), 'body' => $response->body()]);
                return ['status' => 'error', 'message' => 'Failed to fetch data from API. HTTP Status: ' . $response->status(), 'count' => 0];
            }
        } catch (\Exception $e) {
            Log::error('cURL error while fetching NASA FIRMS data.', ['error' => $e->getMessage()]);
            return ['status' => 'error', 'message' => 'cURL error: ' . $e->getMessage(), 'count' => 0];
        }

        // --- Parse and Store Data ---
        $csvData = $response->body();
        $lines = explode("\n", trim($csvData));
        $header = str_getcsv(array_shift($lines)); // Get and remove header

        if (empty($lines) || empty($header)) {
            return ['status' => 'success', 'message' => 'No new fire incidents reported in the last 24 hours.', 'count' => 0];
        }

        $incidents = [];
        $now = Carbon::now();
        $uniqueKeys = [];

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;

            $data = str_getcsv($line);
            if (count($data) !== count($header)) continue; // Skip malformed lines

            $rowData = array_combine($header, $data);

            $timeRaw = str_pad($rowData['acq_time'], 4, '0', STR_PAD_LEFT);
            $hours = substr($timeRaw, 0, 2);
            $minutes = substr($timeRaw, 2, 2);
            $timeFormatted = "{$hours}:{$minutes}:00";

            $incidentData = [
                'latitude' => $rowData['latitude'],
                'longitude' => $rowData['longitude'],
                'brightness' => $rowData['brightness'],
                'scan' => $rowData['scan'],
                'track' => $rowData['track'],
                'acq_date' => $rowData['acq_date'],
                'acq_time' => $timeFormatted,
                'satellite' => $rowData['satellite'],
                'instrument' => 'MODIS',
                'confidence' => $rowData['confidence'],
                'version' => $rowData['version'],
                'bright_t31' => $rowData['bright_t31'],
                'frp' => $rowData['frp'],
                'daynight' => $rowData['daynight'],
                'type' => 0,
                'source' => 'MODIS_NRT',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $key = "{$incidentData['latitude']}-{$incidentData['longitude']}-{$incidentData['acq_date']}-{$incidentData['acq_time']}";
            if (!isset($uniqueKeys[$key])) {
                $incidents[] = $incidentData;
                $uniqueKeys[$key] = true;
            }
        }
        
        if (empty($incidents)) {
             return ['status' => 'success', 'message' => 'Parsed data but found no valid incidents.', 'count' => 0];
        }

        // Use a transaction for efficiency and safety
        DB::transaction(function () use ($incidents) {
            // First, clear out old data to keep the table fresh.
            FireIncident::where('created_at', '<', Carbon::now()->subDays(2))->delete();

            // ** THE FIX IS HERE: Chunk the data before upserting **
            // We break the massive $incidents array into smaller chunks of 500 records.
            $incidentChunks = collect($incidents)->chunk(500);

            // Now we loop through each small chunk and run an upsert operation.
            // This keeps the number of placeholders in each query well below the limit.
            foreach ($incidentChunks as $chunk) {
                FireIncident::upsert(
                    $chunk->toArray(),
                    ['latitude', 'longitude', 'acq_date', 'acq_time'], // Unique columns to identify duplicates
                    ['brightness', 'confidence', 'frp', 'updated_at']  // Columns to update if a duplicate is found
                );
            }
        });
        
        Log::info('Successfully fetched and stored/updated NASA FIRMS data.', ['count' => count($incidents)]);
        return ['status' => 'success', 'message' => 'Successfully updated fire incident data.', 'count' => count($incidents)];
    }
}