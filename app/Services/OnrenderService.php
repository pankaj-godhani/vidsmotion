<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class OnrenderService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = 'GsMf1Iqk3R18SHJCT1xYFIcY1';
        $this->baseUrl = 'https://it2v-api.onrender.com/api/v1';
    }

    /**
     * Upload image to Onrender API
     */
    public function uploadImage(UploadedFile $file): array
    {
        try {
            Log::info('Onrender API Upload Request', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType()
            ]);

            // Try with API key as query parameter
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'X-API-key' => $this->apiKey
            ])->timeout(60) // Increase timeout to 60 seconds
            ->attach(
                'file', $file->getContent(), $file->getClientOriginalName()
            )->post($this->baseUrl . '/images/upload_image');

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Onrender API Upload Success', ['response' => $responseData]);

                return [
                    'success' => true,
                    'image_url' => $responseData['image_url'] ?? $responseData['url'] ?? null,
                    'data' => $responseData
                ];
            } else {
                Log::error('Onrender API Upload Error', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'headers' => $response->headers(),
                    'url' => $this->baseUrl . '/api/v1/images/upload_image'
                ]);

                return [
                    'success' => false,
                    'error' => 'Upload failed: ' . $response->status(),
                    'details' => $response->body(),
                    'url' => $this->baseUrl . '/images/upload_image'
                ];
            }
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
