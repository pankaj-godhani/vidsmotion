<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExploreVideo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ExploreVideoController extends Controller
{
    /**
     * Get all public explore videos with pagination and filtering
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:50',
            'sort' => 'in:newest,oldest,most_viewed,most_liked',
            'filter' => 'in:all,recent,popular',
            'search' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $perPage = $request->query('per_page', 12);
        $sort = $request->query('sort', 'newest');
        $filter = $request->query('filter', 'all');
        $search = $request->query('search');

        $query = ExploreVideo::with('user:id,name,avatar')
            ->where('is_public', true);

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('prompt', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereJsonContains('tags', $search);
            });
        }

        // Apply sorting
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'most_viewed':
                $query->orderBy('views_count', 'desc');
                break;
            case 'most_liked':
                $query->orderBy('likes_count', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Apply filtering
        switch ($filter) {
            case 'recent':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case 'popular':
                $query->where('views_count', '>', 10);
                break;
            case 'all':
            default:
                // No additional filtering
                break;
        }

        $videos = $query->paginate($perPage);

        $items = $videos->map(function ($video) {
            return [
                'id' => $video->id,
                'prompt' => $video->prompt,
                'description' => $video->description,
                'thumbnail_url' => $video->thumbnail_url,
                'video_url' => $video->video_url,
                'duration' => $video->duration,
                'resolution' => $video->resolution,
                'views_count' => $video->views_count,
                'likes_count' => $video->likes_count,
                'tags' => $video->tags,
                'created_at' => $video->created_at,
                'user' => [
                    //'id' => $video->user->id,
                   // 'name' => $video->user->name,
                   // 'avatar' => $video->user->avatar,
                ],
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $items,
                'current_page' => $videos->currentPage(),
                'per_page' => $videos->perPage(),
                'total' => $videos->total(),
                'last_page' => $videos->lastPage(),
            ],
        ]);
    }

    /**
     * Get a specific explore video by ID
     */
    public function show($id): JsonResponse
    {
        $video = ExploreVideo::with('user:id,name,avatar')
            ->where('is_public', true)
            ->find($id);

        if (!$video) {
            return response()->json([
                'success' => false,
                'message' => 'Video not found',
            ], 404);
        }

        // Increment view count
        $video->increment('views_count');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $video->id,
                'prompt' => $video->prompt,
                'description' => $video->description,
                'thumbnail_url' => $video->thumbnail_url,
                'video_url' => $video->video_url,
                'duration' => $video->duration,
                'resolution' => $video->resolution,
                'views_count' => $video->views_count,
                'likes_count' => $video->likes_count,
                'tags' => $video->tags,
                'created_at' => $video->created_at,
                'user' => [
                    'id' => $video->user->id,
                    'name' => $video->user->name,
                    'avatar' => $video->user->avatar,
                ],
            ],
        ]);
    }

    /**
     * Create a new explore video (authenticated users only)
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'prompt' => 'required|string|max:500',
            'video_url' => 'required|string|url',
            'thumbnail_url' => 'nullable|string|url',
            'duration' => 'nullable|integer|min:1',
            'resolution' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'is_public' => 'boolean',
            'original_filename' => 'nullable|string|max:255',
            'file_size' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $video = ExploreVideo::create([
            'user_id' => Auth::id(),
            'prompt' => $request->prompt,
            'video_url' => $request->video_url,
            'thumbnail_url' => $request->thumbnail_url,
            'duration' => $request->duration,
            'resolution' => $request->resolution,
            'description' => $request->description,
            'tags' => $request->tags,
            'is_public' => $request->boolean('is_public', true),
            'original_filename' => $request->original_filename,
            'file_size' => $request->file_size,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Video added to explore successfully',
            'data' => [
                'id' => $video->id,
                'prompt' => $video->prompt,
                'video_url' => $video->video_url,
                'is_public' => $video->is_public,
                'created_at' => $video->created_at,
            ],
        ], 201);
    }

    /**
     * Update an explore video (owner only)
     */
    public function update(Request $request, $id): JsonResponse
    {
        $video = ExploreVideo::where('user_id', Auth::id())->find($id);

        if (!$video) {
            return response()->json([
                'success' => false,
                'message' => 'Video not found or you do not have permission to edit it',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'prompt' => 'sometimes|string|max:500',
            'description' => 'nullable|string|max:1000',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'is_public' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $video->update($request->only([
            'prompt',
            'description',
            'tags',
            'is_public',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Video updated successfully',
            'data' => [
                'id' => $video->id,
                'prompt' => $video->prompt,
                'description' => $video->description,
                'tags' => $video->tags,
                'is_public' => $video->is_public,
                'updated_at' => $video->updated_at,
            ],
        ]);
    }

    /**
     * Delete an explore video (owner only)
     */
    public function destroy($id): JsonResponse
    {
        $video = ExploreVideo::where('user_id', Auth::id())->find($id);

        if (!$video) {
            return response()->json([
                'success' => false,
                'message' => 'Video not found or you do not have permission to delete it',
            ], 404);
        }

        $video->delete();

        return response()->json([
            'success' => true,
            'message' => 'Video deleted successfully',
        ]);
    }

    /**
     * Like/Unlike a video
     */
    public function toggleLike($id): JsonResponse
    {
        $video = ExploreVideo::where('is_public', true)->find($id);

        if (!$video) {
            return response()->json([
                'success' => false,
                'message' => 'Video not found',
            ], 404);
        }

        // For now, we'll just increment/decrement likes
        // In a real app, you'd want a separate likes table to track individual user likes
        $video->increment('likes_count');

        return response()->json([
            'success' => true,
            'message' => 'Video liked successfully',
            'data' => [
                'likes_count' => $video->likes_count,
            ],
        ]);
    }

    /**
     * Get user's own explore videos
     */
    public function myVideos(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 12);
        
        $videos = ExploreVideo::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $items = $videos->map(function ($video) {
            return [
                'id' => $video->id,
                'prompt' => $video->prompt,
                'description' => $video->description,
                'thumbnail_url' => $video->thumbnail_url,
                'video_url' => $video->video_url,
                'duration' => $video->duration,
                'resolution' => $video->resolution,
                'views_count' => $video->views_count,
                'likes_count' => $video->likes_count,
                'tags' => $video->tags,
                'is_public' => $video->is_public,
                'created_at' => $video->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $items,
                'current_page' => $videos->currentPage(),
                'per_page' => $videos->perPage(),
                'total' => $videos->total(),
                'last_page' => $videos->lastPage(),
            ],
        ]);
    }
}