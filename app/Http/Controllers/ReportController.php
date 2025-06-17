<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;
use App\Services\AzureSearchService;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Barryvdh\DomPDF\Facade\Pdf; // <-- IMPORT THE PDF FACADE

class ReportController extends Controller
{
    // PASTE YOUR FULL FFMPEG PATH HERE
    // Example for Linux: '/usr/bin/ffmpeg'
    // Example for Windows: 'C:\\ffmpeg\\bin\\ffmpeg.exe' (use double backslashes)

    private const FFMPEG_PATH = 'D:\\ffmpeg-master-latest-win64-gpl-shared\\ffmpeg-master-latest-win64-gpl-shared\\bin\\ffmpeg.exe';

    /**
     * Main processing function for the audio report.
     * Injects the AzureSearchService for RAG capabilities.
     */
    public function process(Request $request, AzureSearchService $searchService) // <-- Inject service here

    {
        
        $request->validate(['audio' => 'required|file']);

        try {
            $transcript = $this->transcribeAudio($request->file('audio'));
            $analysis = $this->analyzeText($transcript);
            
            // This is the key change: call the new RAG-based suggestion method
            $suggestions = $this->getRagSuggestions($transcript, $analysis['entities'], $searchService);

             // --- START: Database Saving Logic ---
            $report = new Report();
            $report->transcript = $transcript;
            $report->ai_suggested_actions = $suggestions; // Laravel's cast handles JSON encoding automatically
            $report->key_entities = $analysis['entities']; // Also cast to JSON
            $report->user_id = Auth::id();
            $report->save();
            // --- END: Database Saving Logic ---

            
            // MODIFIED RESPONSE: Include the new report ID
            return response()->json([
                'report_id' => $report->id, // <-- ADDED THIS LINE
                'transcript' => $transcript,
                'summary' => $analysis['summary'],
                'entities' => $analysis['entities'],
                'suggestions' => $suggestions,
            ]);
        } catch (Throwable $e) {
            Log::error('Report Processing Failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * NEW METHOD: Handles the PDF export request.
     *
     * @param Report $report The report instance injected by Route Model Binding.
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Report $report)
    {
        // Optional: Add authorization check if needed, e.g., if only the user who created it can download.
        // if (Auth::id() !== $report->user_id) {
        //     abort(403, 'Unauthorized action.');
        // }

        // Load the PDF view with the report data
        $pdf = PDF::loadView('firefighter.report', ['report' => $report]);

        // Define a user-friendly filename
        $filename = 'Incident-Report-' . $report->id . '-' . $report->created_at->format('Ymd-His') . '.pdf';

        // Stream the PDF to the browser for download
        return $pdf->download($filename);
    }


    private function transcribeAudio($audioFile)
    {
        $originalFilename = $audioFile->hashName();
        $originalPath = $audioFile->getRealPath();
        $convertedFilename = 'converted_' . pathinfo($originalFilename, PATHINFO_FILENAME) . '.wav';
        $convertedPath = storage_path('app/temp/' . $convertedFilename);

        Storage::disk('local')->makeDirectory('temp');
        if (!function_exists('shell_exec')) {
            throw new \Exception('The shell_exec function is disabled on this server. FFmpeg cannot be run.');
        }
        $ffmpegCommand = sprintf(
            '%s -i "%s" -acodec pcm_s16le -ar 16000 -ac 1 "%s" 2>&1',
            self::FFMPEG_PATH,
            $originalPath,
            $convertedPath
        );

        $output = shell_exec($ffmpegCommand);
        if (!file_exists($convertedPath) || filesize($convertedPath) === 0) {
            Log::error('FFmpeg conversion failed.', ['command' => $ffmpegCommand, 'output' => $output]);
            throw new \Exception('Failed to convert audio file. Check FFmpeg path and permissions.');
        }

        $speechKey = env('SPEECH_KEY');
        $speechRegion = env('SPEECH_REGION');
        if (!$speechKey || !$speechRegion) {
            throw new \Exception('Azure Speech credentials are not configured in the .env file.');
        }
        $endpoint = "https://{$speechRegion}.stt.speech.microsoft.com/speech/recognition/conversation/cognitiveservices/v1?language=en-US";
        
        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $speechKey,
            'Content-Type' => 'audio/wav; codecs=audio/pcm; samplerate=16000',
        ])->withBody(file_get_contents($convertedPath), 'application/octet-stream')->post($endpoint);

        Storage::disk('local')->delete('temp/' . $convertedFilename);
        
        if ($response->failed()) {
            Log::error('Azure Speech API failed.', ['status' => $response->status(), 'body' => $response->body()]);
            throw new \Exception('Speech-to-text service failed. Check Azure credentials or service status.');
        }

        $result = $response->json();
        if (empty($result['DisplayText'])) {
            throw new \Exception('No speech was detected in the audio.');
        }
        
        return $result['DisplayText'];
    }

    private function analyzeText($text)
    {
        $languageKey = env('LANGUAGE_KEY');
        $languageEndpoint = env('LANGUAGE_ENDPOINT');
        if (!$languageKey || !$languageEndpoint) {
            throw new \Exception('Azure Language credentials are not configured.');
        }

        $summary = $this->getSummary($text, $languageKey, $languageEndpoint);
        $entities = $this->getEntities($text, $languageKey, $languageEndpoint);

        return ['summary' => $summary, 'entities' => $entities];
    }
    
    private function getSummary($text, $key, $endpoint)
    {
        $summaryEndpoint = "{$endpoint}/language/analyze-text/jobs?api-version=2023-04-01";
        $response = Http::withHeaders(['Ocp-Apim-Subscription-Key' => $key])->post($summaryEndpoint, [
            'analysisInput' => ['documents' => [['id' => '1', 'language' => 'en', 'text' => $text]]],
            'tasks' => [['kind' => 'AbstractiveSummarization', 'parameters' => ['sentenceCount' => 3]]]
        ]);

        if ($response->failed()) {
            Log::error('Summary job submission failed.', ['status' => $response->status(), 'body' => $response->body()]);
            return 'Summary service request failed.';
        }

        $jobUrl = $response->header('operation-location');
        $maxAttempts = 10;
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            sleep(1);
            $jobResponse = Http::withHeaders(['Ocp-Apim-Subscription-Key' => $key])->get($jobUrl);
            $status = $jobResponse->json()['status'] ?? 'running';

            if ($status === 'succeeded') {
                return $jobResponse->json()['tasks']['items'][0]['results']['documents'][0]['summaries'][0]['text'] ?? 'Could not generate summary.';
            } elseif ($status === 'failed') {
                Log::error('Summary job failed.', ['jobResponse' => $jobResponse->json()]);
                return 'Summary job failed.';
            }
            $attempt++;
        }

        Log::warning('Summary job timed out.', ['jobUrl' => $jobUrl]);
        return 'Summary job timed out.';
    }

    private function getEntities($text, $key, $endpoint)
    {
        $entitiesEndpoint = "{$endpoint}/language/:analyze-text?api-version=2023-04-01";
         $response = Http::withHeaders(['Ocp-Apim-Subscription-Key' => $key])->post($entitiesEndpoint, [
             'kind' => 'EntityRecognition',
             'analysisInput' => ['documents' => [['id' => '1', 'language' => 'en', 'text' => $text]]],
             'parameters' => ['modelVersion' => 'latest']
         ]);
        return $response->json()['results']['documents'][0]['entities'] ?? [];
    }

    /**
     * Generates suggestions using the RAG pattern.
     * It first retrieves relevant documents from Azure Search and then uses them
     * to augment the prompt for the OpenAI model.
     *
     * @param string $transcript The user's transcribed report.
     * @param array $entities The entities extracted from the transcript.
     * @param AzureSearchService $searchService The service to query for documents.
     * @return array The list of suggestions.
     */
    private function getRagSuggestions(string $transcript, array $entities, AzureSearchService $searchService): array
    {
        // 1. RETRIEVAL: Query Azure Search to get relevant documents
        $searchResults = $searchService->search($transcript, 3);
        
        $contextString = "No relevant documents found.";
        if (!empty($searchResults)) {
            $contextString = "RELEVANT CONTEXT FROM OFFICIAL DOCUMENTS:\n";
            foreach ($searchResults as $index => $result) {
                $title = $result['title'] ?? 'Untitled';
                $content = $result['content'] ?? 'No content.';
                // Use highlights from semantic search if available, otherwise use full content
                $bestPassage = $result['@search.captions'][0]['text'] ?? $content;
                
                $contextString .= ($index + 1) . ". DOCUMENT TITLE: {$title}\n   RELEVANT EXCERPT: \"{$bestPassage}\"\n\n";
            }
        }

        // 2. AUGMENTED GENERATION: Build a new, more powerful prompt
        $openAiKey = env('AZURE_OPENAI_KEY');
        $openAiEndpoint = env('AZURE_OPENAI_ENDPOINT');
        $deploymentName = env('AZURE_OPENAI_DEPLOYMENT_NAME');
        if (!$openAiKey || !$openAiEndpoint || !$deploymentName) {
            Log::error('Azure OpenAI credentials not configured.');
            return [];
        }

        $endpoint = "{$openAiEndpoint}/openai/deployments/{$deploymentName}/chat/completions?api-version=2024-02-15-preview";
        
        // The RAG prompt is much more specific and powerful
        $prompt = "You are an AI assistant for a wildfire incident command center. A firefighter has submitted a field report. Your task is to provide clear, actionable suggestions based **strictly** on the provided official documents and the report. Prioritize information from the documents over general knowledge. If the documents provide a direct procedure, reference it.

{$contextString}
---
FIREFIGHTER FIELD REPORT:
\"{$transcript}\"
---

Based on the official documents and the report, provide 3-4 concise suggestions for the command center. Format your response as a JSON array of objects. Each object must have two keys: 'icon' (a Font Awesome class name like 'fas fa-helicopter') and 'suggestion' (a string with the suggestion text).";

        $response = Http::withHeaders([
            'api-key' => $openAiKey,
            'Content-Type' => 'application/json',
        ])->post($endpoint, [
            'messages' => [['role' => 'system', 'content' => $prompt], ['role' => 'user', 'content' => 'Provide the suggestions in the specified JSON format based on the documents and the report.']],
            'max_tokens' => 400, // Increased slightly for potentially longer, context-aware responses
            'temperature' => 0.3, // Lower temperature for more factual, less creative responses
            'response_format' => ['type' => 'json_object']
        ]);

        if ($response->failed()) {
            Log::error('Azure OpenAI RAG API failed.', ['status' => $response->status(), 'body' => $response->body()]);
            return [];
        }

        $content = $response->json()['choices'][0]['message']['content'] ?? null;
        if ($content) {
            $jsonContent = preg_replace('/^```json\s*|\s*```$/', '', $content);
            $decoded = json_decode($jsonContent, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded['suggestions'] ?? $decoded;
            }
            Log::error('Invalid JSON response from RAG-based OpenAI.', ['content' => $jsonContent]);
        }

        Log::warning('No suggestions generated from RAG-based OpenAI.', ['content' => $content]);
        return [];
    }

      public function history()
    {
        // Fetch all reports, ordered by the newest first.
        // We only select the columns needed by the frontend for efficiency.
        $reports = Report::latest()->get([
            'id', 
            'transcript', 
            'ai_suggested_actions', 
            'created_at'
        ]);

        return response()->json($reports);
    }
}