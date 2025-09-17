<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class OnrenderService
{
    private $apiKey;
    private $baseUrl;
    private $headerName;
    private $authScheme;
    private $fileField;
    private $method;

    public function __construct()
    {
        // Prefer environment variables; fall back to sane defaults
        $this->apiKey = config('services.onrender.key', env('ONRENDER_API_KEY', ''));
        $this->baseUrl = rtrim(config('services.onrender.base_url', env('ONRENDER_BASE_URL', 'https://it2v-api.onrender.com/api/v1')), '/');
        $this->headerName = config('services.onrender.header', env('ONRENDER_HEADER', 'X-API-Key'));
        $this->authScheme = config('services.onrender.auth_scheme', env('ONRENDER_AUTH_SCHEME'));
        $this->fileField = config('services.onrender.file_field', env('ONRENDER_FILE_FIELD', 'file'));
        $this->method = strtoupper(config('services.onrender.method', env('ONRENDER_METHOD', 'POST')));
    }

    /**
     * Upload image to Onrender API
     */
    public function uploadImage(UploadedFile $file): array
    {
        try {
            if (empty($this->baseUrl) || empty($this->apiKey)) {
                return [
                    'success' => false,
                    'error' => 'Onrender base URL or API key is not configured',
                ];
            }

            Log::info('Onrender API Upload Request', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'base_url' => $this->baseUrl,
            ]);



            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Accept' => 'application/json'
            ])->timeout(180)->connectTimeout(30)->retry(2, 3000)->attach(
                'file',  // key name expected by API
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )
            ->post($this->baseUrl);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Onrender API Upload Success', ['response' => $responseData]);

                return [
                    'success' => true,
                    'image_url' => $responseData['image_url'] ?? $responseData['url'] ?? null,
                    'data' => $responseData,
                ];
            }

            Log::error('Onrender API Upload Error', [
                'status' => $response->status(),
                'response' => $response->body(),
                'headers' => $response->headers(),
                'url' => $this->baseUrl,
            ]);

            return [
                'success' => false,
                'error' => 'Upload failed: ' . $response->status(),
                'details' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('Onrender Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Service error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Upload image from URL
     */
    public function uploadImageFromUrl(string $imageUrl): array
    {
        try {
            // Download the image first
            $imageContent = Http::get($imageUrl);

            if (!$imageContent->successful()) {
                return [
                    'success' => false,
                    'error' => 'Failed to download image from URL'
                ];
            }

            // Create a temporary file
            $tempFile = tmpfile();
            fwrite($tempFile, $imageContent->body());
            $tempPath = stream_get_meta_data($tempFile)['uri'];

            // Create UploadedFile instance
            $uploadedFile = new UploadedFile(
                $tempPath,
                basename($imageUrl),
                $imageContent->header('Content-Type'),
                null,
                true
            );

            $result = $this->uploadImage($uploadedFile);

            // Clean up
            fclose($tempFile);

            return $result;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Service error: ' . $e->getMessage()
            ];
        }
    }
}
