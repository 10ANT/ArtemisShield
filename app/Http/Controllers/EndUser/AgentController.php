<?php

namespace App\Http\Controllers\EndUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;
use Exception;

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
        // Use the main project endpoint
        $this->endpoint = rtrim(config('services.azure.ai_project_endpoint'), '/');
        
        // Use the specific credentials for the status update/end-user agent
        $this->agentId = config('services.azure.status_update_agent.id');
        $this->tenantId = config('services.azure.status_update_agent.tenant_id');
        $this->clientId = config('services.azure.status_update_agent.client_id');
        $this->clientSecret = config('services.azure.status_update_agent.client_secret');
        
        // API version can be shared
        $this->apiVersion = config('services.azure.ai_api_version');

        if (!$this->endpoint || !$this->agentId || !$this->apiVersion || !$this->tenantId || !$this->clientId || !$this->clientSecret) {
            Log::emergency('End-User Azure AI Service OAuth credentials are not fully configured.');
            throw new Exception('The Community Support AI Service is not fully configured on the server.');
        }
    }

    private function getAccessToken(): string
    {
        // Use a unique cache key for this agent's token
        $cacheKey = 'azure_ai_access_token_end_user';
        return Cache::remember($cacheKey, 3000, function () {
            Log::info('Requesting new Azure AD Access Token for End-User Agent.');
            $url = "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token";
            $response = Http::asForm()->post($url, [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope' => 'https://ai.azure.com/.default',
                'grant_type' => 'client_credentials',
            ])->throw();
            Log::info('Successfully retrieved new access token for End-User Agent.');
            return $response->json('access_token');
        });
    }

    private function makeApiCall(string $method, string $path, array $data = []): Response
    {
        $accessToken = $this->getAccessToken();
        $fullUrl = $this->endpoint . '/' . ltrim($path, '/');
        
        $request = Http::withToken($accessToken)
            ->withHeaders(['Content-Type' => 'application/json']);

        if (strtolower($method) === 'get') {
            $queryParams = array_merge(['api-version' => $this->apiVersion], $data);
            $response = $request->get($fullUrl, $queryParams);
        } else {
            $response = $request->withQueryParameters(['api-version' => $this->apiVersion])
                ->$method($fullUrl, $data);
        }

        Log::debug("End-User Agent: Attempting Azure API Call", [
            'method' => $method, 
            'url' => (string) $response->effectiveUri()
        ]);
        
        $response->throw();
        return $response;
    }

    public function chat(Request $request) {
        $userMessage = $request->input('message');
        // Use a unique session key for the end-user's thread
        $threadId = $request->session()->get('end_user_azure_thread_id');
        
        Log::info("EndUserAgentController chat method initiated.", ['thread_id' => $threadId]);
        
        try {
            if (!$threadId) {
                $threadId = $this->createThread();
                $request->session()->put('end_user_azure_thread_id', $threadId);
            }
            $this->createMessage($threadId, 'user', $userMessage);
            // This agent doesn't take custom tools from the frontend, it uses its defaults.
            $run = $this->createRun($threadId, $this->agentId);
            return $this->pollRun($threadId, $run['id']);
        } catch (RequestException $e) {
            Log::error('End-User Agent: API request to Azure failed: ' . $e->getMessage(), ['response' => $e->response->body()]);
            return response()->json(['error' => 'The request to the AI service failed.'], 502);
        } catch (Exception $e) {
            Log::error('End-User Agent: A non-API error occurred: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'An unexpected server error occurred.'], 500);
        }
    }

    private function pollRun($threadId, $runId) {
        $startTime = time();
        while (time() - $startTime < 60) {
            $run = $this->getRun($threadId, $runId);
            Log::info("End-User Agent: Polling run {$runId}, status: {$run['status']}");
            if (in_array($run['status'], ['queued', 'in_progress'])) {
                sleep(1); continue;
            }
            // This agent is not expected to use tools, but we handle the case anyway.
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

    private function createRun(string $threadId, string $agentId): array {
        $payload = ['assistant_id' => $agentId];
        Log::info('End-User Agent: Creating run with agent default tools.');
        return $this->makeApiCall('post', "threads/{$threadId}/runs", $payload)->json();
    }

    private function getRun(string $threadId, string $runId): array {
        return $this->makeApiCall('get', "threads/{$threadId}/runs/{$runId}")->json();
    }

    private function listMessages(string $threadId): array {
        return $this->makeApiCall('get', "threads/{$threadId}/messages", ['order' => 'asc'])->json()['data'] ?? [];
    }

    public function reset(Request $request) {
        // Use the unique session key
        $request->session()->forget('end_user_azure_thread_id');
        Log::info('End-user chat session has been reset by the user.');
        return response()->json(['status' => 'success', 'message' => 'Conversation reset.']);
    }
}