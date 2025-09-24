<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExploreVideo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExploreController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) max(1, min(60, (int) $request->query('per_page', 12)));
        $filter = trim((string) $request->query('filter', 'all'));
        $sortBy = trim((string) $request->query('sort', 'latest'));
        $search = trim((string) $request->query('q', ''));

        $query = ExploreVideo::query()
            ->where(function ($q) {
                $q->where('is_public', true)
                  ->orWhereNull('is_public');
            });

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('prompt', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        if ($filter !== '' && $filter !== 'all') {
            $query->whereJsonContains('tags', $filter);
        }

        switch ($sortBy) {
            case 'popular':
                $query->orderByDesc('views_count');
                break;
            case 'liked':
                $query->orderByDesc('likes_count');
                break;
            case 'oldest':
                $query->orderBy('created_at');
                break;
            case 'latest':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $videos = $query->paginate($perPage);

        $items = collect($videos->items())->map(function (ExploreVideo $v) {
            return [
                'id' => $v->id,
                'author' => optional($v->user)->name,
                'prompt' => $v->prompt,
                'thumbnail' => $v->thumbnail_url,
                'videoUrl' => $v->video_url,
                'views' => (int) ($v->views_count ?? 0),
                'likes' => (int) ($v->likes_count ?? 0),
                'duration' => (int) ($v->duration ?? 0),
                'resolution' => $v->resolution,
                'created_at' => $v->created_at,
                'tags' => $v->tags ?? [],
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


