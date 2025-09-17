<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use App\Services\PiapiService;
use App\Services\CreditService;
use App\Services\OnrenderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
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
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:1000',
            'image_url' => 'required|url', // Image URL is required for generation
            'duration' => 'nullable|integer|min:1|max:10',
            'is_recreate' => 'nullable|boolean',
            'original_video_id' => 'nullable|string',
            'original_video_title' => 'nullable|string',
        ]);

        try {
            $userId = auth()->id();
            $prompt = $request->input('prompt');
            $imageUrl = $request->input('image_url');
            $duration = $request->input('duration', 6);
            $isRecreate = $request->input('is_recreate', false);
            $originalVideoId = $request->input('original_video_id');
            $originalVideoTitle = $request->input('original_video_title');

            // Enforce credits before starting generation (base 50 + 10 if prompt > 50 chars)
            $promptLength = strlen(trim((string)$prompt));
            $requiredCredits = 50 + ($promptLength > 80 ? 10 : 0);
            $creditService = new CreditService();
            if (!$creditService->hasCredits($request->user(), $requiredCredits)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient credits. Please buy credits to generate a video.',
                    'data' => [
                        'required' => $requiredCredits,
                        'current' => $request->user()->credits,
                    ],
                ], 402);
            }

            // Create upload record
            $upload = Upload::create([
                'user_id' => $userId,
                'filename' => Str::uuid() . '.mp4',
                'original_filename' => 'generated_video_' . time() . '.mp4',
                'file_path' => null, // Will be set when video is ready
                'file_type' => 'video/mp4',
                'file_size' => null, // Will be set when video is ready
                'status' => 'pending',
                'metadata' => [
                    'prompt' => $prompt,
                    'duration' => $duration,
                    'is_recreate' => $isRecreate,
                    'original_video_id' => $originalVideoId,
                    'original_video_title' => $originalVideoTitle,
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
                $currentMetadata = $this->getMetadataArray($upload->metadata);
                $upload->update([
                    'status' => 'processing',
                    'metadata' => array_merge($currentMetadata, [
                        'piapi_task_id' => $result['task_id'],
                        'image_url' => $imageUrl,
                        'required_credits' => $requiredCredits,
                    ])
                ]);

                Log::info('Video generation started', [
                    'upload_id' => $upload->id,
                    'task_id' => $result['task_id'],
                    'user_id' => $userId
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Video generation started successfully',
                    'data' => [
                        'id' => $upload->id,
                        'task_id' => $result['task_id'],
                        'status' => $upload->status,
                        'prompt' => $prompt,
                        'duration' => $duration,
                        'created_at' => $upload->created_at,
                    ]
                ], 201);
            } else {
                // Update upload status to failed
                $upload->update(['status' => 'failed']);

                Log::error('Video generation failed', [
                    'upload_id' => $upload->id,
                    'error' => $result['error'],
                    'user_id' => $userId
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Video generation failed',
                    'error' => $result['error'],
                    'data' => [
                        'id' => $upload->id,
                        'status' => $upload->status,
                    ]
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Upload controller exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload reference image only
     */
    public function uploadImage(Request $request): JsonResponse
    {
        // Allow longer processing for external API uploads
        try { @set_time_limit(180); } catch (\Throwable $e) { /* ignore on restricted environments */ }

        $request->validate([
            'reference_image' => 'required|file|image|max:10240', // 10MB max for images
        ]);

        try {
            // User is authenticated via Sanctum middleware
            $user = $request->user();

            $onrenderService = new OnrenderService();
            $imageResult = $onrenderService->uploadImage($request->file('reference_image'));

            if ($imageResult['success']) {
                $imageUrl = $imageResult['image_url'];
                Log::info('Image uploaded successfully', ['image_url' => $imageUrl, 'user_id' => $user->id]);

                return response()->json([
                    'success' => true,
                    'message' => 'Image uploaded successfully',
                    'data' => [
                        'image_url' => $imageUrl,
                        'uploaded_at' => now()->toISOString()
                    ]
                ], 201);
            } else {
                Log::error('Image upload failed', [
                    'error' => $imageResult['error'] ?? null,
                    'details' => $imageResult['details'] ?? null,
                    'user_id' => $user->id
                ]);

                $status = str_contains((string)($imageResult['error'] ?? ''), '422') ? 422 : 500;

                return response()->json([
                    'success' => false,
                    'message' => 'Image upload failed',
                    'error' => $imageResult['error'] ?? 'Unknown error',
                    'details' => $imageResult['details'] ?? null,
                ], $status);
            }

        } catch (\Exception $e) {
            Log::error('Image upload exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $request->user()?->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading the image',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
