<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use App\Services\PiapiService;
use App\Services\CreditService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class StatusController extends Controller
{
    public function status(Request $request, $taskId): JsonResponse
    {
        // Find upload by task_id stored in metadata
        $upload = Upload::whereJsonContains('metadata->piapi_task_id', $taskId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$upload) {
            return response()->json([
                'success' => false,
                'message' => 'Upload not found or access denied',
            ], 404);
        }

        // Check Piapi API for latest status
        $piapiService = new PiapiService();
        $piapiStatus = $piapiService->getTaskStatus($taskId);

        $latestVideoUrl = null;
        $latestStatus = $upload->status;
        $latestMetadata = $upload->metadata ?? [];

        if ($piapiStatus['success']) {
            $piapiData = $piapiStatus['data']['data'] ?? [];
            $latestStatus = $piapiData['status'] ?? $upload->status;

            // Extract video URL and progress from Piapi response
            $output = $piapiData['output'] ?? [];
            $latestVideoUrl = $output['video_url'] ?? null;
            $progressPercent = $output['percent'] ?? 0;

            // Update upload record with latest info if video is ready
            if ($latestVideoUrl && $latestStatus === 'completed') {
                $currentMetadata = $latestMetadata;
                $updatedMetadata = array_merge($currentMetadata, [
                    'video_url' => $latestVideoUrl,
                    'piapi_status' => $latestStatus,
                    'last_checked' => now()->toISOString(),
                    'piapi_response' => $piapiData
                ]);

                $upload->update([
                    'status' => 'completed',
                    'metadata' => $updatedMetadata
                ]);

                $latestMetadata = $updatedMetadata;

                // Deduct credits once per completed upload
                try {
                    $user = $request->user();
                    if ($user) {
                        $creditService = new CreditService();
                        $required = (int)($latestMetadata['required_credits'] ?? 80);
                        if ($required < 50) { $required = 50; }
                        $creditService->deductCredits($user, $required, 'Video generation completed');
                    }
                } catch (\Throwable $e) {
                    Log::error('Credit deduction failed after completion', [
                        'upload_id' => $upload->id,
                        'error' => $e->getMessage(),
                    ]);
                }

                Log::info('Video completed via status check', [
                    'upload_id' => $upload->id,
                    'task_id' => $taskId,
                    'video_url' => $latestVideoUrl
                ]);
            } else if ($latestStatus !== $upload->status) {
                // Update status if it changed
                $currentMetadata = $latestMetadata;
                $updatedMetadata = array_merge($currentMetadata, [
                    'piapi_status' => $latestStatus,
                    'last_checked' => now()->toISOString()
                ]);

                $upload->update([
                    'status' => $latestStatus,
                    'metadata' => $updatedMetadata
                ]);

                $latestMetadata = $updatedMetadata;
            }
        }

        // Get metadata for additional info
        $piapiTaskId = $latestMetadata['piapi_task_id'] ?? null;
        $imageUrl = $latestMetadata['image_url'] ?? null;
        $videoUrl = $latestVideoUrl ?? $latestMetadata['video_url'] ?? null;

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $upload->id,
                'task_id' => $piapiTaskId,
                'filename' => $upload->original_filename,
                'status' => $latestStatus,
                'uploaded_at' => $upload->created_at,
                'processed_at' => $upload->processed_at,
                'error_message' => $upload->error_message,
                'image_url' => $imageUrl,
                'video_url' => $videoUrl,
                'progress_percent' => $progressPercent,
                'metadata' => $latestMetadata
            ]
        ]);
    }
}
