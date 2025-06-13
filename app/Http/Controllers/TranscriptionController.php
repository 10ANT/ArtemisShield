<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class TranscriptionController extends Controller
{
    // Ensure this path matches the one in your ReportController
    private const FFMPEG_PATH = 'D:\ffmpeg-master-latest-win64-gpl-shared\bin\ffmpeg.exe';

    /**
     * Handles the audio file upload, transcribes it, and returns the text.
     */
    public function transcribe(Request $request)
    {
        $request->validate(['audio' => 'required|file']);

        Log::info('TranscriptionController: Received audio file for transcription.');

        try {
            $transcript = $this->transcribeAudio($request->file('audio'));

            Log::info('TranscriptionController: Successfully transcribed audio.', ['transcript' => $transcript]);

            return response()->json([
                'transcript' => $transcript,
            ]);
        } catch (Throwable $e) {
            Log::error('TranscriptionController: Transcription failed.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Return a clean JSON error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * This private method contains the logic to convert and transcribe audio.
     * It's copied from your ReportController to make this controller self-contained.
     */
    private function transcribeAudio($audioFile)
    {
        $originalFilename = $audioFile->hashName();
        $originalPath = $audioFile->getRealPath();

        $convertedFilename = 'transcribed_' . pathinfo($originalFilename, PATHINFO_FILENAME) . '.wav';
        $convertedPath = storage_path('app/temp/' . $convertedFilename);

        Storage::disk('local')->makeDirectory('temp');

        if (!function_exists('shell_exec')) {
            throw new \Exception('The shell_exec function is disabled. FFmpeg cannot be run.');
        }

        $ffmpegCommand = sprintf(
            '%s -i "%s" -acodec pcm_s16le -ar 16000 -ac 1 "%s" 2>&1',
            self::FFMPEG_PATH,
            $originalPath,
            $convertedPath
        );

        $output = shell_exec($ffmpegCommand);
        
        if (!file_exists($convertedPath) || filesize($convertedPath) === 0) {
            Log::error('FFmpeg conversion failed.', [
                'command' => $ffmpegCommand,
                'output' => $output
            ]);
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
        ])->withBody(
            file_get_contents($convertedPath), 'application/octet-stream'
        )->post($endpoint);

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
}