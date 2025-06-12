<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'nasa' => [
    'api_key' => env('NASA_API_KEY'),
],
'openweather' => [
    'api_key' => env('OPENWEATHER_API_KEY'),
],
   'cesium' => [
        'ion_access_token' => env('CESIUM_ION_ACCESS_TOKEN'),
    ],
    
      'azure' => [
        'ai_project_endpoint' => env('AZURE_AI_PROJECT_ENDPOINT'),
        'ai_agent_id' => env('AZURE_AI_AGENT_ID'),
        'ai_api_key' => env('AZURE_AI_API_KEY'),
        'ai_api_version' => 'v1',


            //  new credentials for OAuth
        'tenant_id' => env('AZURE_TENANT_ID'),
        'client_id' => env('AZURE_CLIENT_ID'),
        'client_secret' => env('AZURE_CLIENT_SECRET'),
        
        // Keep the old API key in case other services need it
  
    ],




];
