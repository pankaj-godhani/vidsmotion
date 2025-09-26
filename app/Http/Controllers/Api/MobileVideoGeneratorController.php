<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use App\Services\CreditService;
use App\Services\PiapiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class  MobileVideoGeneratorController extends Controller
{
    /**
     * Generate video for mobile app
     */
    public function generateVideo(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'prompt' => 'required|string|min:5|max:500',
                'duration' => 'integer|min:3|max:10',
                'image_url' => 'nullable|url|max:2048',
                'is_recreate' => 'boolean',
                'original_video_id' => 'nullable|string',
                'original_video_title' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            $prompt = $request->input('prompt');
            $imageUrl = $request->input('image_url');
            $duration = $request->input('duration', 6);
            $isRecreate = $request->input('is_recreate', false);
            $originalVideoId = $request->input('original_video_id');
            $originalVideoTitle = $request->input('original_video_title');

            // Calculate required credits
            $promptLength = strlen(trim($prompt));
            $requiredCredits = 50 + ($promptLength > 80 ? 10 : 0);

            // Check if user has sufficient credits
            $creditService = new CreditService();
            if (!$creditService->hasCredits($user, $requiredCredits)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient credits. Please purchase credits to generate a video.',
                    'data' => [
                        'required' => $requiredCredits,
                        'current' => $user->credits,
                        'shortfall' => $requiredCredits - $user->credits,
                    ],
                ], 402);
            }

            // Create upload record
            $upload = Upload::create([
                'user_id' => $user->id,
                'filename' => Str::uuid() . '.mp4',
                'original_filename' => 'mobile_generated_video_' . time() . '.mp4',
                'file_path' => null,
                'file_type' => 'video/mp4',
                'file_size' => null,
                'status' => 'pending',
                'metadata' => [
                    'prompt' => $prompt,
                    'duration' => $duration,
                    'is_recreate' => $isRecreate,
                    'original_video_id' => $originalVideoId,
                    'original_video_title' => $originalVideoTitle,
                    'platform' => 'mobile',
                    'required_credits' => $requiredCredits,
                ]
            ]);

            // Generate video using Piapi API
            $piapiService = new PiapiService();
            $videoData = [
                'prompt' => $prompt,
                'duration' => $duration,
            ];

            if (!empty($imageUrl)) {
                $videoData['image_url'] = $imageUrl;
            }

            $result = $piapiService->generateVideo($videoData);

            if ($result['success']) {
                // Update upload with task ID
                $currentMetadata = $upload->metadata ?? [];
                $upload->update([
                    'status' => 'processing',
                    'metadata' => array_merge($currentMetadata, [
                        'piapi_task_id' => $result['task_id'],
                        'image_url' => $imageUrl,
                        'generation_started_at' => now()->toISOString(),
                    ])
                ]);

                Log::info('Mobile video generation started', [
                    'upload_id' => $upload->id,
                    'task_id' => $result['task_id'],
                    'user_id' => $user->id,
                    'prompt_length' => $promptLength,
                    'required_credits' => $requiredCredits,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Video generation started successfully',
                    'data' => [
                        'upload_id' => $upload->id,
                        'task_id' => $result['task_id'],
                        'status' => $upload->status,
                        'prompt' => $prompt,
                        'duration' => $duration,
                        'required_credits' => $requiredCredits,
                        'user_credits' => $user->credits,
                        'created_at' => $upload->created_at,
                        'estimated_completion' => now()->addMinutes(3)->toISOString(),
                    ]
                ], 201);
            } else {
                // Update upload status to failed
                $upload->update([
                    'status' => 'failed',
                    'metadata' => array_merge($currentMetadata ?? [], [
                        'error' => $result['error'] ?? 'Unknown error',
                        'failed_at' => now()->toISOString(),
                    ])
                ]);

                Log::error('Mobile video generation failed', [
                    'upload_id' => $upload->id,
                    'user_id' => $user->id,
                    'error' => $result['error'] ?? 'Unknown error',
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Video generation failed',
                    'data' => [
                        'upload_id' => $upload->id,
                        'error' => $result['error'] ?? 'Unknown error',
                    ]
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Mobile video generation error', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check video generation status for mobile app
     */
    public function checkStatus(Request $request, string $taskId): JsonResponse
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            // Find upload by task ID
            $upload = Upload::where('user_id', $user->id)
                ->whereJsonContains('metadata->piapi_task_id', $taskId)
                ->first();

            if (!$upload) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video generation task not found'
                ], 404);
            }

            // Get status from Piapi API
            $piapiService = new PiapiService();
            $piapiStatus = $piapiService->getTaskStatus($taskId);

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
                        'piapi_response' => $piapiData,
                        'completed_at' => now()->toISOString(),
                    ]);

                    $upload->update([
                        'status' => 'completed',
                        'metadata' => $updatedMetadata
                    ]);

                    $latestMetadata = $updatedMetadata;

                    // Deduct credits once per completed upload
                    try {
                        $required = (int)($latestMetadata['required_credits'] ?? 50);
                        if ($required < 50) { $required = 50; }
                        $creditService = new CreditService();
                        $creditService->deductCredits($user, $required, 'Mobile video generation completed');
                    } catch (\Throwable $e) {
                        Log::error('Mobile credit deduction failed after completion', [
                            'upload_id' => $upload->id,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    Log::info('Mobile video completed via status check', [
                        'upload_id' => $upload->id,
                        'task_id' => $taskId,
                        'user_id' => $user->id,
                        'video_url' => $latestVideoUrl,
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'data' => [
                        'upload_id' => $upload->id,
                        'task_id' => $taskId,
                        'status' => $latestStatus,
                        'progress_percent' => $progressPercent,
                        'video_url' => $latestVideoUrl,
                        'prompt' => $latestMetadata['prompt'] ?? '',
                        'duration' => $latestMetadata['duration'] ?? 6,
                        'created_at' => $upload->created_at,
                        'updated_at' => $upload->updated_at,
                        'user_credits' => $user->fresh()->credits,
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to check video status',
                    'data' => [
                        'upload_id' => $upload->id,
                        'task_id' => $taskId,
                        'status' => $upload->status,
                        'error' => $piapiStatus['error'] ?? 'Unknown error',
                    ]
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Mobile video status check error', [
                'error' => $e->getMessage(),
                'task_id' => $taskId,
                'user_id' => $request->user()?->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's video generation history for mobile app
     */
    public function getHistory(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            $perPage = (int) max(1, min(50, (int) $request->query('per_page', 20)));
            $status = $request->query('status'); // pending, processing, completed, failed

            $query = Upload::where('user_id', $user->id)
                ->whereJsonContains('metadata->platform', 'mobile');

            if ($status) {
                $query->where('status', $status);
            }

            $uploads = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);

            $items = collect($uploads->items())->map(function (Upload $upload) {
                $metadata = $upload->metadata ?? [];
                return [
                    'id' => $upload->id,
                    'task_id' => $metadata['piapi_task_id'] ?? null,
                    'status' => $upload->status,
                    'prompt' => $metadata['prompt'] ?? '',
                    'duration' => $metadata['duration'] ?? 6,
                    'video_url' => $metadata['video_url'] ?? null,
                    'progress_percent' => $metadata['piapi_response']['output']['percent'] ?? 0,
                    'required_credits' => $metadata['required_credits'] ?? 50,
                    'created_at' => $upload->created_at,
                    'updated_at' => $upload->updated_at,
                    'completed_at' => $metadata['completed_at'] ?? null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $items,
                    'current_page' => $uploads->currentPage(),
                    'per_page' => $uploads->perPage(),
                    'total' => $uploads->total(),
                    'last_page' => $uploads->lastPage(),
                    'user_credits' => $user->credits,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Mobile video history error', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel video generation for mobile app
     */
    public function cancelGeneration(Request $request, string $taskId): JsonResponse
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            // Find upload by task ID
            $upload = Upload::where('user_id', $user->id)
                ->whereJsonContains('metadata->piapi_task_id', $taskId)
                ->first();

            if (!$upload) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video generation task not found'
                ], 404);
            }

            // Only allow cancellation if still processing
            if ($upload->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel completed video generation'
                ], 400);
            }

            // Update upload status to cancelled
            $currentMetadata = $upload->metadata ?? [];
            $upload->update([
                'status' => 'cancelled',
                'metadata' => array_merge($currentMetadata, [
                    'cancelled_at' => now()->toISOString(),
                    'cancelled_by' => 'user',
                ])
            ]);

            Log::info('Mobile video generation cancelled', [
                'upload_id' => $upload->id,
                'task_id' => $taskId,
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Video generation cancelled successfully',
                'data' => [
                    'upload_id' => $upload->id,
                    'task_id' => $taskId,
                    'status' => 'cancelled',
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Mobile video cancellation error', [
                'error' => $e->getMessage(),
                'task_id' => $taskId,
                'user_id' => $request->user()?->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
