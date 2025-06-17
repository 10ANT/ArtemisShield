<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class AzureMLService
{
    protected $endpointUrl;
    protected $apiKey;
    protected $deploymentName;

    public function __construct()
    {
        $this->endpointUrl = config('azureml.endpoint_url');
        $this->apiKey = config('azureml.api_key');
        $this->deploymentName = config('azureml.deployment_name');

        if (!$this->endpointUrl || !$this->apiKey || !$this->deploymentName) {
            Log::error('Azure ML Service credentials are not fully configured. Please check your .env file for endpoint, key, and deployment name.');
            throw new Exception('Azure ML Service credentials are not configured.');
        }
    }

    /**
     * Get a prediction from the Azure ML model.
     *
     * @param array $features The input features for the model.
     * @return float|null The predicted value or null on failure.
     */
    public function getPrediction(array $features): ?float
    {
        $columnOrder = [
            "latitude",
            "longitude",
            "brightness",
            "confidence",
            "bright_t31",
            "daynight"
        ];

        $featureValues = [];
        foreach ($columnOrder as $column) {
            if ($column === 'daynight') {
                $featureValues[] = (string)($features[$column] ?? 'D');
            } else {
                $featureValues[] = (float)($features[$column] ?? 0);
            }
        }
        
        $requestData = [
            'input_data' => [
                'columns' => $columnOrder,
                'index' => [0],
                'data' => [
                    $featureValues 
                ],
            ]
        ];

        Log::info('Sending request to Azure ML Endpoint', [
            'url' => $this->endpointUrl,
            'deployment' => $this->deploymentName,
            'data_structure' => $requestData
        ]);

        try {
            $response = Http::withToken($this->apiKey, 'Bearer')
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'azureml-model-deployment' => $this->deploymentName,
                ])
                ->timeout(30)
                ->post($this->endpointUrl, $requestData);

            if ($response->successful()) {
                $predictionData = $response->json();
                Log::info('Received successful response from Azure ML', ['response' => $predictionData]);
                
                // === THE FIX IS HERE ===
                // This logic now handles multiple possible response formats from Azure.

                $resultArray = null;

                // 1. Check for the standard object format: { "result": [...] } or { "prediction": [...] }
                if (isset($predictionData['result']) && is_array($predictionData['result'])) {
                    $resultArray = $predictionData['result'];
                } elseif (isset($predictionData['prediction']) && is_array($predictionData['prediction'])) {
                    $resultArray = $predictionData['prediction'];
                } 
                // 2. Fallback to check if the response IS the array itself: [...]
                elseif (is_array($predictionData)) {
                    $resultArray = $predictionData;
                }

                // If we found a valid, non-empty array, return the first number.
                if (is_array($resultArray) && !empty($resultArray)) {
                    return (float) $resultArray[0];
                }

                // If we couldn't parse the response, log an error.
                Log::error('Azure ML response was in an unexpected format. Could not extract prediction.', ['response' => $predictionData]);
                return null;

            } else {
                Log::error('Azure ML request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }

        } catch (Exception $e) {
            Log::error('Exception caught while calling Azure ML Service', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
}