<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use App\Services\PiapiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class PiapiWebhookController extends Controller
{
    private function getMetadataArray($metadata): array
    {
        if (is_array($metadata)) {
            return $metadata;
        }
        if (is_string($metadata)) {
            return json_decode($metadata, true) ?? [];
        }
        return [];
    }
    public function handleWebhook(Request $request): JsonResponse
    {
        try {
            Log::info('Piapi Webhook Received', ['data' => $request->all()]);

            $taskId = $request->input('task_id');
            $status = (string) $request->input('status', 'processing');
            $rawResult = $request->input('result');
            // Normalize webhook result to a safe array
            $result = is_array($rawResult)
                ? $rawResult
                : (is_string($rawResult) ? (json_decode($rawResult, true) ?: []) : []);

            if (!$taskId) {
                Log::error('Piapi Webhook: Missing task_id');
                return response()->json(['error' => 'Missing task_id'], 400);
            }

            // Find upload by task ID
            $upload = Upload::whereJsonContains('metadata->piapi_task_id', $taskId)->first();

            if (!$upload) {
                Log::error('Piapi Webhook: Upload not found', ['task_id' => $taskId]);
                return response()->json(['error' => 'Upload not found'], 404);
            }

            Log::info('Piapi Webhook: Processing upload', [
                'upload_id' => $upload->id,
                'task_id' => $taskId,
                'status' => $status
            ]);

            switch ($status) {
                case 'completed':
                    $this->handleCompletedVideo($upload, $result);
                    break;
                case 'failed':
                    $this->handleFailedVideo($upload, $result);
                    break;
                case 'processing':
                    $this->handleProcessingVideo($upload, $result);
                    break;
                default:
                    Log::warning('Piapi Webhook: Unknown status', ['status' => $status]);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Piapi Webhook Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    private function handleCompletedVideo(Upload $upload, array $result): void
    {
        try {
            $videoUrl = $result['video_url'] ?? $result['output_url'] ?? null;

            if (!$videoUrl) {
                Log::error('Piapi Webhook: No video URL in result', ['result' => $result]);
                $upload->update(['status' => 'failed']);
                return;
            }

            // Download the video
            $videoResponse = Http::timeout(300)->get($videoUrl);

            if (!$videoResponse->successful()) {
                Log::error('Piapi Webhook: Failed to download video', [
                    'url' => $videoUrl,
                    'status' => $videoResponse->status()
                ]);
                $upload->update(['status' => 'failed']);
                return;
            }

            // Save video to storage
            $filename = $upload->filename;
            $filePath = 'videos/' . $filename;

            Storage::disk('public')->put($filePath, $videoResponse->body());

            // Update upload record
            $currentMetadata = $this->getMetadataArray($upload->metadata);
            $upload->update([
                'status' => 'completed',
                'file_path' => $filePath,
                'file_size' => strlen($videoResponse->body()),
                'metadata' => array_merge($currentMetadata, [
                    'video_url' => $videoUrl,
                    'completed_at' => now()->toISOString(),
                    'webhook_result' => $result
                ])
            ]);

            Log::info('Piapi Webhook: Video completed successfully', [
                'upload_id' => $upload->id,
                'file_path' => $filePath,
                'file_size' => strlen($videoResponse->body())
            ]);

        } catch (\Exception $e) {
            Log::error('Piapi Webhook: Error handling completed video', [
                'upload_id' => $upload->id,
                'error' => $e->getMessage()
            ]);
            $upload->update(['status' => 'failed']);
        }
    }

    private function handleFailedVideo(Upload $upload, array $result): void
    {
        $currentMetadata = $this->getMetadataArray($upload->metadata);
        $upload->update([
            'status' => 'failed',
            'metadata' => array_merge($currentMetadata, [
                'failed_at' => now()->toISOString(),
                'failure_reason' => $result['error'] ?? 'Unknown error',
                'webhook_result' => $result
            ])
        ]);

        Log::info('Piapi Webhook: Video generation failed', [
            'upload_id' => $upload->id,
            'reason' => $result['error'] ?? 'Unknown error'
        ]);
    }

    private function handleProcessingVideo(Upload $upload, array $result): void
    {
        $currentMetadata = $this->getMetadataArray($upload->metadata);
        $upload->update([
            'status' => 'processing',
            'metadata' => array_merge($currentMetadata, [
                'processing_update' => now()->toISOString(),
                'webhook_result' => $result
            ])
        ]);

        Log::info('Piapi Webhook: Video processing update', [
            'upload_id' => $upload->id,
            'progress' => $result['progress'] ?? 'Unknown'
        ]);
    }
}
