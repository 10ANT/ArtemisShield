<?php

namespace App\Console\Commands;

use App\Models\CommunityRisk;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchCommunityRiskData extends Command
{
    protected $signature = 'community:fetch-risk-data';
    protected $description = 'Fetches community risk data from wildfirerisk.org and stores it in the local database.';

    public function handle()
    {
        $this->info("Fetching community risk data from the source...");

        // **THE DEFINITIVE FIX:** This is the latest correct URL as of June 2024.
        $dataUrl = 'https://wildfirerisk.org/data/geojson/rc-communities-v2024.1.0.geojson';

        try {
            // Increased timeout for this large file download
            $response = Http::timeout(300)->get($dataUrl);

            if (!$response->successful()) {
                $this->error("Failed to download data. Status code: " . $response->status());
                $this->error("Please check the URL in the FetchCommunityRiskData command file. It may have been updated again.");
                return 1;
            }

            $data = $response->json();

            // Check if the expected 'features' key exists
            if (!isset($data['features'])) {
                $this->error("Downloaded data is not in the expected GeoJSON format.");
                return 1;
            }
            
            $features = $data['features'];
            $this->info("Data downloaded successfully. Starting database import (this may take a minute)...");
            
            // Use a database transaction and disable model events for a massive speed boost
            DB::transaction(function () use ($features) {
                // Truncate the table to ensure a fresh, clean import
                CommunityRisk::truncate(); 
                
                $this->output->progressStart(count($features));

                foreach ($features as $feature) {
                    $props = $feature['properties'];
                    $coords = $feature['geometry']['coordinates'];

                    // Use 'create' directly within the loop for simplicity here
                    CommunityRisk::create([
                        'name' => $props['name'],
                        'county_name' => $props['county_name'],
                        'state_abbreviation' => $props['state_abbreviation'],
                        'population' => $props['population'],
                        'risk_to_homes_text' => $props['risk_to_homes_text'],
                        'whp_text' => $props['whp_text'],
                        'exposure' => $props['exposure'],
                        'longitude' => $coords[0],
                        'latitude' => $coords[1],
                    ]);
                    $this->output->progressAdvance();
                }
            });

            $this->output->progressFinish();
            $this->info("Successfully imported all community risk data.");
            return 0;

        } catch (\Exception $e) {
            Log::error('Failed to fetch community risk data: ' . $e->getMessage());
            $this->error("An error occurred: " . $e->getMessage());
            return 1;
        }
    }
}