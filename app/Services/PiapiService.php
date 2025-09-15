<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PiapiService
{
    private $apiKey;
    private $baseUrl;
    private $webhookUrl;

    public function __construct()
    {
        $this->apiKey = '84fa350c93a5a704b56d687b3376510498b23e42c95dd94eb8adf7b1849b1a05';
        $this->baseUrl = 'https://api.piapi.ai/api/v1';
        $this->webhookUrl = config('app.url') . '/api/webhook/piapi';

        // For development, use ngrok or similar service for webhook
        if (config('app.env') === 'local') {
            // You can set this in your .env file: WEBHOOK_URL=https://your-ngrok-url.ngrok.io
            $this->webhookUrl = config('app.webhook_url', $this->webhookUrl);
        }
    }

    /**
     * Generate video using Piapi API
     */
    public function generateVideo(array $data): array
    {
        try {
            // Use different model based on whether image is provided
            $model = !empty($data['image_url']) ? 'i2v-02' : 't2v-02';

            $payload = [
                'model' => 'hailuo',
                'task_type' => 'video_generation',
                'input' => [
                    'prompt' => $data['prompt'],
                    'model' => $model,
                    'expand_prompt' => true,
                    'duration' => $data['duration'] ?? 6,
                    'resolution' => 768
                ],
                'config' => [
                    'service_mode' => 'public',
                    'webhook_config' => [
                        'endpoint' => $this->webhookUrl,
                        'secret' => ''
                    ]
                ]
            ];

            // Only add image_url if provided
            if (!empty($data['image_url'])) {
                $payload['input']['image_url'] = $data['image_url'];
            }

            Log::info('Piapi API Request', ['payload' => $payload]);

            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/task', $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Piapi API Success', ['response' => $responseData]);

                return [
                    'success' => true,
                    'task_id' => $responseData['data']['task_id'] ?? null,
                    'data' => $responseData
                ];
            } else {
                Log::error('Piapi API Error', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return [
                    'success' => false,
                    'error' => 'API request failed: ' . $response->status(),
                    'details' => $response->body()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Piapi Service Exception', [
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
     * Get task status from Piapi API
     */
    public function getTaskStatus(string $taskId): array
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->get($this->baseUrl . '/task/' . $taskId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Failed to get task status: ' . $response->status()
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Service error: ' . $e->getMessage()
            ];
        }
    }
}
