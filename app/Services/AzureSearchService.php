<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AzureSearchService
{
    protected string $endpoint;
    protected string $apiKey;
    protected string $indexName;

    public function __construct()
    {
        $this->endpoint = config('services.azure.search.endpoint');
        $this->apiKey = config('services.azure.search.key');
        $this->indexName = config('services.azure.search.index_name');

        if (!$this->endpoint || !$this->apiKey || !$this->indexName) {
            throw new \Exception('Azure Search credentials are not configured in services config or .env file.');
        }
    }

    /**
     * Performs a semantic search on the Azure AI Search index.
     *
     * @param string $query The search query text.
     * @param int $limit The maximum number of results to return.
     * @return array The search results.
     */
    public function search(string $query, int $limit = 3): array
    {
        $url = "{$this->endpoint}/indexes/{$this->indexName}/docs/search?api-version=2023-11-01";

        $payload = [
            'search' => $query,
            'queryType' => 'semantic', // Use the powerful semantic search
            'semanticConfiguration' => 'default', // The name of the semantic config in your index
            'queryLanguage' => 'en-us',
            'captions' => 'extractive', // Gets highlighted summary passages
            'answers' => 'extractive|count-3', // Tries to find direct answers
            'top' => $limit,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'api-key' => $this->apiKey,
        ])->post($url, $payload);

        if ($response->failed()) {
            Log::error('Azure AI Search request failed.', [
                'status' => $response->status(),
                'response' => $response->body(),
                'query' => $query
            ]);
            return [];
        }

        // Return the 'value' array which contains the documents
        return $response->json()['value'] ?? [];
    }
}