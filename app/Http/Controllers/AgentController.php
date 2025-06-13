<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;

class AgentController extends Controller
{
    protected $endpoint;
    protected $agentId;
    protected $apiVersion;
    protected $tenantId;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->endpoint = rtrim(config('services.azure.ai_project_endpoint'), '/');
        $this->agentId = config('services.azure.ai_agent_id');
        $this->apiVersion = config('services.azure.ai_api_version');
        $this->tenantId = config('services.azure.tenant_id');
        $this->clientId = config('services.azure.client_id');
        $this->clientSecret = config('services.azure.client_secret');

        if (!$this->endpoint || !$this->agentId || !$this->apiVersion || !$this->tenantId || !$this->clientId || !$this->clientSecret) {
            Log::emergency('Azure AI Service OAuth credentials are not fully configured.');
            throw new Exception('Azure AI Service is not fully configured on the server.');
        }
    }

    private function getAccessToken(): string
    {
        return Cache::remember('azure_ai_access_token', 3000, function () {
            Log::info('Requesting new Azure AD Access Token.');
            $url = "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token";
            $response = Http::asForm()->post($url, [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope' => 'https://ai.azure.com/.default',
                'grant_type' => 'client_credentials',
            ])->throw();
            Log::info('Successfully retrieved new access token.');
            return $response->json('access_token');
        });
    }

    /**
     * This is the final, corrected implementation.
     */
    private function makeApiCall(string $method, string $path, array $data = []): Response
    {
        $accessToken = $this->getAccessToken();
        $fullUrl = $this->endpoint . '/' . ltrim($path, '/');
        
        $request = Http::withToken($accessToken)
            ->withHeaders(['Content-Type' => 'application/json']);

        if (strtolower($method) === 'get') {
            // For GET, all data is merged into the query parameters.
            $queryParams = array_merge(['api-version' => $this->apiVersion], $data);
            $response = $request->get($fullUrl, $queryParams);
        } else {
            // For POST, PUT, etc., the api-version is a query param and the data is the body.
            $response = $request->withQueryParameters(['api-version' => $this->apiVersion])
                ->$method($fullUrl, $data);
        }

        // Log the effective URL to confirm the query string is present.
        Log::debug("Attempting Azure API Call", [
            'method' => $method, 
            'url' => (string) $response->effectiveUri()
        ]);

        Log::debug("Azure API Call Response", [
            'status' => $response->status(),
            'response_body' => $response->json() ?? $response->body()
        ]);
        
        $response->throw();
        return $response;
    }

        public function chat(Request $request) {
        $userMessage = $request->input('message');
        // ADD THIS LINE to get the tools from the request
        $tools = $request->input('tools');
        $threadId = $request->session()->get('azure_thread_id');
        
        Log::info("AgentController chat method initiated.", ['thread_id' => $threadId]);
        
        try {
            if (!$threadId) {
                $threadId = $this->createThread();
                $request->session()->put('azure_thread_id', $threadId);
            }
            $this->createMessage($threadId, 'user', $userMessage);
            // MODIFIED: Pass the tools to the createRun method
            $run = $this->createRun($threadId, $this->agentId, $tools);
            return $this->pollRun($threadId, $run['id']);
        } catch (RequestException $e) {
            Log::error('API request to Azure failed: ' . $e->getMessage(), ['response' => $e->response->body()]);
            return response()->json(['error' => 'The request to the AI service failed.'], 502);
        } catch (Exception $e) {
            Log::error('A non-API error occurred: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'An unexpected server error occurred.'], 500);
        }
    }
    

 public function submitToolOutput(Request $request) {
        $threadId = $request->session()->get('azure_thread_id');
        $runId = $request->input('run_id');
        $toolOutputs = $request->input('tool_outputs');
        try {
            // CORRECTED: The path is now correctly constructed for the submitToolOutputs action.
            $run = $this->makeApiCall("post", "threads/{$threadId}/runs/{$runId}/submit_tool_outputs", ['tool_outputs' => $toolOutputs])->json();
            return $this->pollRun($threadId, $run['id']);
        } catch (RequestException $e) {
            Log::error('API request failed on tool submission: ' . $e->getMessage(), ['response' => $e->response->body()]);
            return response()->json(['error' => 'Failed to submit tool results.'], 502);
        }
    }


    private function pollRun($threadId, $runId) {
        $startTime = time();
        while (time() - $startTime < 60) {
            $run = $this->getRun($threadId, $runId);
            Log::info("Polling run {$runId}, status: {$run['status']}");
            if (in_array($run['status'], ['queued', 'in_progress'])) {
                sleep(1); continue;
            }
            if ($run['status'] === 'requires_action') return response()->json($run);
            if ($run['status'] === 'completed') return response()->json(['status' => 'completed', 'messages' => $this->listMessages($threadId)]);
            if (in_array($run['status'], ['failed', 'cancelled', 'expired'])) {
                throw new Exception("Run failed: " . ($run['last_error']['message'] ?? 'Unknown Error'));
            }
        }
        throw new Exception("Run polling timed out.");
    }

    private function createThread(): string {
        return $this->makeApiCall('post', 'threads')->json()['id'];
    }

    private function createMessage(string $threadId, string $role, string $content): array {
        return $this->makeApiCall('post', "threads/{$threadId}/messages", ['role' => $role, 'content' => $content])->json();
    }

    private function createRun(string $threadId, string $agentId, ?array $tools = null): array {
        $payload = ['assistant_id' => $agentId];

        if (!empty($tools)) {
            $payload['tools'] = $tools;
            Log::info('Creating run with a custom tool override.', ['tool_count' => count($tools)]);
        } else {
            Log::info('Creating run with agent default tools.');
        }

        return $this->makeApiCall('post', "threads/{$threadId}/runs", $payload)->json();
    }

    private function getRun(string $threadId, string $runId): array {
        return $this->makeApiCall('get', "threads/{$threadId}/runs/{$runId}")->json();
    }

    private function listMessages(string $threadId): array {
        return $this->makeApiCall('get', "threads/{$threadId}/messages", ['order' => 'asc'])->json()['data'] ?? [];
    }

    public function reset(Request $request)
{
    $request->session()->forget('azure_thread_id');
    Log::info('Chat session has been reset by the user.');
    return response()->json(['status' => 'success', 'message' => 'Conversation reset.']);
}
}