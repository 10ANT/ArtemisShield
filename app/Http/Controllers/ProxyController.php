<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProxyController extends Controller
{
    /**
     * Acts as a proxy to fetch images from the NOAA CDN to avoid client-side CORS issues.
     *
     * @param string $path The full path of the image on the NOAA server.
     * @return \Illuminate\Http\Response
     */
    public function getNoaaImage($path)
    {
        // Reconstruct the full NOAA URL
        $noaaUrl = 'https://cdn.star.nesdis.noaa.gov/' . $path;

        // Add the original query string if it exists
        if (request()->getQueryString()) {
            $noaaUrl .= '?' . request()->getQueryString();
        }

        Log::info('Proxying request for NOAA image: ' . $noaaUrl);

        try {
            $response = Http::timeout(20)->get($noaaUrl);

            if ($response->successful()) {
                // Return the image content with the correct content type
                return response($response->body())
                    ->header('Content-Type', $response->header('Content-Type') ?: 'image/jpeg');
            }

            // Log and return an error if NOAA fetch failed
            Log::error('Failed to fetch image from NOAA.', [
                'status' => $response->status(),
                'url' => $noaaUrl
            ]);
            return response('Failed to fetch image from NOAA.', $response->status());

        } catch (\Exception $e) {
            Log::error('Exception in NOAA proxy.', [
                'message' => $e->getMessage(),
                'url' => $noaaUrl
            ]);
            return response('Server error during image proxy.', 500);
        }
    }
}