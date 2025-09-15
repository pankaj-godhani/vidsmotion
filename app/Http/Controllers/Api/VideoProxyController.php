<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VideoProxyController extends Controller
{
    /**
     * Proxy video requests to bypass CORS issues
     */
    public function proxy(Request $request): Response
    {
        $videoUrl = $request->query('url');

        if (!$videoUrl) {
            return response('Video URL is required', 400);
        }

        // Validate that the URL is from the expected domain
        if (!str_contains($videoUrl, 'cdn.hailuoai.video')) {
            return response('Invalid video URL', 403);
        }

        try {
            Log::info('Video proxy request', ['url' => $videoUrl]);

            // Make request to the video URL
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'video/mp4,video/*,*/*',
                    'Accept-Encoding' => 'identity',
                    'Range' => $request->header('Range', ''),
                ])
                ->get($videoUrl);

            if (!$response->successful()) {
                Log::error('Video proxy failed', [
                    'url' => $videoUrl,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return response('Video not found', 404);
            }

            // Get content type from the response
            $contentType = $response->header('Content-Type', 'video/mp4');
            $contentLength = $response->header('Content-Length');
            $acceptRanges = $response->header('Accept-Ranges', 'bytes');

            // Prepare response headers
            $headers = [
                'Content-Type' => $contentType,
                'Accept-Ranges' => $acceptRanges,
                'Cache-Control' => 'public, max-age=3600',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, HEAD, OPTIONS',
                'Access-Control-Allow-Headers' => 'Range',
            ];

            if ($contentLength) {
                $headers['Content-Length'] = $contentLength;
            }

            // Handle range requests for video seeking
            if ($request->hasHeader('Range')) {
                $range = $request->header('Range');
                $headers['Content-Range'] = $response->header('Content-Range');
                $headers['Content-Length'] = $response->header('Content-Length');

                return response($response->body(), 206, $headers);
            }

            Log::info('Video proxy success', [
                'url' => $videoUrl,
                'content_type' => $contentType,
                'content_length' => $contentLength
            ]);

            return response($response->body(), 200, $headers);

        } catch (\Exception $e) {
            Log::error('Video proxy exception', [
                'url' => $videoUrl,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response('Internal server error', 500);
        }
    }
}
