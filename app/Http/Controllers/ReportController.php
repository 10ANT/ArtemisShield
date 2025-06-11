<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ReportController extends Controller
{
    // PASTE YOUR FULL FFMPEG PATH HERE
    // Example for Linux: '/usr/bin/ffmpeg'
    // Example for Windows: 'C:\\ffmpeg\\bin\\ffmpeg.exe' (use double backslashes)
private const FFMPEG_PATH = 'D:\\ffmpeg-master-latest-win64-gpl-shared\\ffmpeg-master-latest-win64-gpl-shared\\bin\\ffmpeg.exe';
    public function process(Request $request)
    {
        // (This part remains the same)
        $request->validate(['audio' => 'required|file']);

        try {
            $transcript = $this->transcribeAudio($request->file('audio'));
            $analysis = $this->analyzeText($transcript);
            $suggestions = $this->getAiSuggestions($transcript, $analysis['entities']);

            return response()->json([
                'transcript' => $transcript,
                'summary' => $analysis['summary'],
                'entities' => $analysis['entities'],
                'suggestions' => $suggestions,
            ]);
        } catch (Throwable $e) {
            Log::error('Report Processing Failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            // Return a cleaner JSON error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function transcribeAudio($audioFile)
    {
        $originalFilename = $audioFile->hashName();
        $originalPath = $audioFile->getRealPath(); // Use the direct path from the upload temp dir
        
        $convertedFilename = 'converted_' . pathinfo($originalFilename, PATHINFO_FILENAME) . '.wav';
        $convertedPath = storage_path('app/temp/' . $convertedFilename);

        // Ensure the temp directory exists
        Storage::disk('local')->makeDirectory('temp');

        // Check if shell_exec is available
        if (!function_exists('shell_exec')) {
            throw new \Exception('The shell_exec function is disabled on this server. FFmpeg cannot be run.');
        }
        
        // Build the command with the full path to FFmpeg
        $ffmpegCommand = sprintf(
            '%s -i "%s" -acodec pcm_s16le -ar 16000 -ac 1 "%s" 2>&1',
            self::FFMPEG_PATH,
            $originalPath,
            $convertedPath
        );

        // Execute the command
        $output = shell_exec($ffmpegCommand);
        
        if (!file_exists($convertedPath) || filesize($convertedPath) === 0) {
            // Log the command and its output for debugging
            Log::error('FFmpeg conversion failed.', [
                'command' => $ffmpegCommand,
                'output' => $output
            ]);
            throw new \Exception('Failed to convert audio file. Check FFmpeg path and permissions.');
        }

        // Send the converted WAV file to Azure
        $speechKey = env('SPEECH_KEY');
        $speechRegion = env('SPEECH_REGION');
        if (!$speechKey || !$speechRegion) {
            throw new \Exception('Azure Speech credentials are not configured in the .env file.');
        }
        $endpoint = "https://{$speechRegion}.stt.speech.microsoft.com/speech/recognition/conversation/cognitiveservices/v1?language=en-US";
        
        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $speechKey,
            'Content-Type' => 'audio/wav; codecs=audio/pcm; samplerate=16000',
        ])->withBody(
            file_get_contents($convertedPath), 'application/octet-stream'
        )->post($endpoint);

        // Cleanup the converted file immediately
        Storage::disk('local')->delete('temp/' . $convertedFilename);
        
        if ($response->failed()) {
            Log::error('Azure Speech API failed.', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            throw new \Exception('Speech-to-text service failed. Check Azure credentials or service status.');
        }

        $result = $response->json();
        if (empty($result['DisplayText'])) {
            throw new \Exception('No speech was detected in the audio.');
        }
        
        return $result['DisplayText'];
    }

    // --- The other methods (analyzeText, getAiSuggestions, etc.) remain the same ---
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
        sleep(1); // Wait 1 second between attempts
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

   private function getAiSuggestions($transcript, $entities)
{
    $openAiKey = env('AZURE_OPENAI_KEY');
    $openAiEndpoint = env('AZURE_OPENAI_ENDPOINT');
    $deploymentName = env('AZURE_OPENAI_DEPLOYMENT_NAME');
    if (!$openAiKey || !$openAiEndpoint || !$deploymentName) {
        Log::error('Azure OpenAI credentials not configured.');
        return [];
    }

    $endpoint = "{$openAiEndpoint}/openai/deployments/{$deploymentName}/chat/completions?api-version=2024-02-15-preview";
    $entitiesText = implode(', ', array_map(fn($e) => "{$e['text']} ({$e['category']})", $entities));
    $prompt = "You are an AI assistant for a wildfire incident command center. A firefighter has submitted the following field report. \n\nTRANSCRIPT: \"{$transcript}\"\n\nEXTRACTED ENTITIES: {$entitiesText}\n\nBased on this information, provide 3-4 clear, actionable, and concise suggestions for the command center. Format your response as a JSON array of objects. Each object must have two keys: 'icon' (a Font Awesome class name like 'fas fa-helicopter') and 'suggestion' (a string with the suggestion text).";

    $response = Http::withHeaders([
        'api-key' => $openAiKey,
        'Content-Type' => 'application/json',
    ])->post($endpoint, [
        'messages' => [['role' => 'system', 'content' => $prompt], ['role' => 'user', 'content' => 'Provide the suggestions in the specified JSON format.']],
        'max_tokens' => 300, 'temperature' => 0.5, 'response_format' => ['type' => 'json_object']
    ]);

    if ($response->failed()) {
        Log::error('Azure OpenAI API failed.', ['status' => $response->status(), 'body' => $response->body()]);
        return [];
    }

    $content = $response->json()['choices'][0]['message']['content'] ?? null;
    if ($content) {
        $jsonContent = preg_replace('/^```json\s*|\s*```$/', '', $content);
        $decoded = json_decode($jsonContent, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded['suggestions'] ?? $decoded; // Handle nested or direct suggestions
        }
        Log::error('Invalid JSON response from OpenAI.', ['content' => $jsonContent]);
    }

    Log::warning('No suggestions generated from OpenAI.', ['content' => $content]);
    return [];
}
}