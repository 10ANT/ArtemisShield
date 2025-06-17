<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Azure Machine Learning Service Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for the Azure ML service.
    | These values are pulled from your .env file.
    |
    */

    'endpoint_url' => env('AZURE_ML_ENDPOINT_URL'),

    'api_key' => env('AZURE_ML_API_KEY'),

    'deployment_name' => env('AZURE_ML_DEPLOYMENT_NAME'),




      // Classification model for spread prediction
    'spread_endpoint_url' => env('AZURE_ML_SPREAD_ENDPOINT_URL'),
    'spread_api_key' => env('AZURE_ML_SPREAD_API_KEY'),
    'spread_deployment_name' => env('AZURE_ML_SPREAD_DEPLOYMENT_NAME'),
];