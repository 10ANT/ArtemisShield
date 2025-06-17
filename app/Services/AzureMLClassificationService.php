<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class AzureMLClassificationService
{
    protected $endpointUrl;
    protected $apiKey;
    protected $deploymentName;

    public function __construct()
    {
        $this->endpointUrl = config('azureml.spread_endpoint_url');
        $this->apiKey = config('azureml.spread_api_key');
        $this->deploymentName = config('azureml.spread_deployment_name');

        if (!$this->endpointUrl || !$this->apiKey || !$this->deploymentName) {
            Log::error('Azure ML Classification Service credentials are not configured. Please check your .env file for spread prediction variables.');
            throw new Exception('Azure ML Classification Service credentials are not configured.');
        }
    }

    public function getSpreadPrediction(array $fireProperties): ?string
    {
        try {
            $derivedInputs = $this->deriveInputs($fireProperties);
            $requestData = [
                'input_data' => [
                    'columns' => array_keys($derivedInputs),
                    'index' => [0],
                    'data' => [array_values($derivedInputs)],
                ]
            ];

            Log::info('Sending request to Azure ML Classification Endpoint', [
                'url' => $this->endpointUrl,
                'deployment' => $this->deploymentName,
                'data_structure' => $requestData
            ]);

            $response = Http::withToken($this->apiKey, 'Bearer')
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'azureml-model-deployment' => $this->deploymentName,
                ])
                ->timeout(30)
                ->post($this->endpointUrl, $requestData);

            if ($response->successful()) {
                $predictionData = $response->json();
                Log::info('Received successful response from Azure ML Classification', ['response' => $predictionData]);
                
                if (is_array($predictionData) && !empty($predictionData)) {
                    return (string) $predictionData[0];
                }

                Log::error('Azure ML Classification response was in an unexpected format.', ['response' => $predictionData]);
                return null;
            }

            Log::error('Azure ML Classification request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return null;

        } catch (Exception $e) {
            Log::error('Exception in AzureMLClassificationService', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    private function deriveInputs(array $props): array
    {
        $defaultCauseCode = 13;
        $defaultOwnerCode = 14;
        $defaultOwnerDescr = 'MISSING/NOT SPECIFIED';
        $defaultState = 'N/A';
        
        $discoveryDate = isset($props['attr_FireDiscoveryDateTime'])
            ? Carbon::createFromTimestamp($props['attr_FireDiscoveryDateTime'] / 1000)
            : Carbon::now();

        $fire_year = $discoveryDate->year;
        $discovery_doy = $discoveryDate->dayOfYear;
        $discovery_month = $discoveryDate->month;
        $discovery_day_of_week = $discoveryDate->dayOfWeek;
        $discovery_time_bin = $this->getTimeBin($discoveryDate->hour);
        $stat_cause_code = $this->getCauseCode($props['attr_FireCause'] ?? '', $defaultCauseCode);

        // === THE FIX IS HERE ===
        // We now correctly check for the most reliable lat/lon keys first.
        $latitude = $props['attr_InitialLatitude'] ?? ($props['poly_Latitude'] ?? 0);
        $longitude = $props['attr_InitialLongitude'] ?? ($props['poly_Longitude'] ?? 0);
        
        $state = $props['POOState'] ?? ($props['poly_Inc_State'] ?? $defaultState);
        $county = $props['POOCounty'] ?? ($props['FIPS_NAME'] ?? null);
        $fips_code = $props['POOFips'] ?? ($props['FIPS_CODE'] ?? null);

        $owner_code = (int)($props['POOOwnerCode'] ?? ($props['own_Owner'] ?? $defaultOwnerCode));
        $owner_descr = $props['POOOwnerKind'] ?? ($props['own_Agency'] ?? $defaultOwnerDescr);

        return [
          "fire_year" => (int)$fire_year,
          "discovery_doy" => (int)$discovery_doy,
          "stat_cause_code" => (int)$stat_cause_code,
          "latitude" => (float)$latitude,
          "longitude" => (float)$longitude,
          "owner_code" => (int)$owner_code,
          "owner_descr" => (string)$owner_descr,
          "state" => (string)$state,
          "county" => $county,
          "fips_code" => $fips_code,
          "discovery_month" => (int)$discovery_month,
          "discovery_day_of_week" => (int)$discovery_day_of_week,
          "discovery_time_bin" => (string)$discovery_time_bin
        ];
    }
    
    private function getCauseCode(string $description, int $default): int {
        $causeMap = [
            'arson' => 7, 'equipment use' => 2, 'miscellaneous' => 9,
            'missing/undefined' => 13, 'lightning' => 1, 'campfire' => 4,
            'debris burning' => 5, 'smoking' => 3, 'children' => 8,
            'powerline' => 11, 'undetermined' => 13 // Map undetermined to missing/undefined
        ];
        return $causeMap[strtolower($description)] ?? $default;
    }
    
    private function getTimeBin(int $hour): string {
        if ($hour >= 5 && $hour < 12) return 'Morning';
        if ($hour >= 12 && $hour < 17) return 'Afternoon';
        if ($hour >= 17 && $hour < 21) return 'Evening';
        return 'Night';
    }
}