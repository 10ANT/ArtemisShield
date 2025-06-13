<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AzureSearchService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ChatController extends Controller
{
    /**
     * Handles an incoming chat message, uses RAG to find an answer, and returns it.
     *
     * @param Request $request
     * @param AzureSearchService $searchService
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request, AzureSearchService $searchService)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');

        try {
            $aiResponse = $this->getRagResponse($userMessage, $searchService);
            
            return response()->json(['reply' => $aiResponse]);

        } catch (Throwable $e) {
            Log::error('Chat RAG processing failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Sorry, I encountered an error trying to process your request. Please try again later.'], 500);
        }
    }

    /**
     * Generates a conversational response using the RAG pattern.
     * It retrieves relevant documents from Azure Search and then uses them
     * to augment the prompt for the OpenAI model to answer a user's question.
     *
     * @param string $userMessage The user's question.
     * @param AzureSearchService $searchService The service to query for documents.
     * @return string The AI-generated conversational response.
     */
    private function getRagResponse(string $userMessage, AzureSearchService $searchService): string
    {
        // --- START DEBUG LOGGING ---
        Log::info('--- New Chat Request ---');
        Log::info('User Message: ' . $userMessage);
        // --- END DEBUG LOGGING ---

        // 1. RETRIEVAL: Query Azure Search to get relevant documents based on the user's message.
        $searchResults = $searchService->search($userMessage, 4); // Get up to 4 relevant chunks
        
        // --- START DEBUG LOGGING ---
        // Log the results from Azure Search to see what was found
        Log::info('Azure Search Results: ' . json_encode($searchResults, JSON_PRETTY_PRINT));
        // --- END DEBUG LOGGING ---

        $contextString = "No relevant documents found.";
        if (!empty($searchResults)) {
            $contextString = "RELEVANT CONTEXT FROM OFFICIAL DOCUMENTS:\n";
            foreach ($searchResults as $index => $result) {
                $title = $result['title'] ?? 'Untitled Document';
                // Use highlights from semantic search if available, otherwise use full content chunk
                $bestPassage = $result['@search.captions'][0]['text'] ?? $result['content'];
                
                $contextString .= ($index + 1) . ". FROM '{$title}': \"{$bestPassage}\"\n\n";
            }
        }

        // 2. AUGMENTED GENERATION: Build a prompt for conversational response.
        $openAiKey = env('AZURE_OPENAI_KEY');
        $openAiEndpoint = env('AZURE_OPENAI_ENDPOINT');
        $deploymentName = env('AZURE_OPENAI_DEPLOYMENT_NAME');

        if (!$openAiKey || !$openAiEndpoint || !$deploymentName) {
            Log::error('Azure OpenAI credentials not configured for chat.');
            throw new \Exception('The AI chat service is not configured correctly.');
        }

        $endpoint = "{$openAiEndpoint}/openai/deployments/{$deploymentName}/chat/completions?api-version=2024-02-15-preview";
        
        $prompt = "You are Artemis, an AI assistant for a wildfire incident command center. A user is asking a question. Your task is to answer the question based **strictly** on the provided context from official documents. Do not use any outside knowledge. If the context does not contain the answer, explicitly state that the information is not available in the provided documents. Provide a concise, helpful, and conversational response as a single string.

{$contextString}
---
USER'S QUESTION:
\"{$userMessage}\"
---

Based on the official documents, answer the user's question. If you cannot, say so.";

        // --- START DEBUG LOGGING ---
        Log::info('Final Prompt Sent to OpenAI: ' . $prompt);
        // --- END DEBUG LOGGING ---

        $response = Http::withHeaders([
            'api-key' => $openAiKey,
            'Content-Type' => 'application/json',
        ])->post($endpoint, [
            'messages' => [
                ['role' => 'system', 'content' => $prompt],
                ['role' => 'user', 'content' => $userMessage]
            ],
            'max_tokens' => 300,
            'temperature' => 0.2,
        ]);

        if ($response->failed()) {
            Log::error('Azure OpenAI Chat RAG API failed.', ['status' => $response->status(), 'body' => $response->body()]);
            throw new \Exception('The AI model failed to generate a response.');
        }

        return $response->json()['choices'][0]['message']['content'] ?? 'I could not generate a response at this time.';
    }
}