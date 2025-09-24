<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function stats(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $data = [
            'total' => Upload::where('user_id', $userId)->count(),
            'completed' => Upload::where('user_id', $userId)->where('status', 'completed')->count(),
            'processing' => Upload::where('user_id', $userId)->whereIn('status', ['pending', 'processing'])->count(),
            'failed' => Upload::where('user_id', $userId)->where('status', 'failed')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function myFilesStats(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $data = [
            'total' => Upload::where('user_id', $userId)->count(),
            'completed' => Upload::where('user_id', $userId)->where('status', 'completed')->count(),
            'processing' => Upload::where('user_id', $userId)->whereIn('status', ['pending', 'processing'])->count(),
            'failed' => Upload::where('user_id', $userId)->where('status', 'failed')->count(),
        ];
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function uploads(Request $request): JsonResponse
    {
        $userId = auth()->id();

        $uploads = Upload::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $uploads
        ]);
    }

    public function credits(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'credits' => $request->user()->credits,
            ]
        ]);
    }

    public function myFiles(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $perPage = (int) max(1, min(100, (int) $request->query('per_page', 10)));

        $uploads = Upload::where('user_id', $userId)
            ->where('status', 'completed')
            ->orderByDesc('created_at')
            ->paginate($perPage);

        $items = collect($uploads->items())->map(function (Upload $u) {
            $downloadApiUrl = url('/api/token-download/' . $u->id);
            return [
                'id' => $u->id,
                'filename' => $u->original_filename ?: $u->filename,
                'prompt' => $u->prompt,
                'file_size' => $u->file_size,
                'created_at' => $u->created_at,
                'video_url' => $u->video_url,
                'download_url' => $downloadApiUrl,
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
            ]
        ]);
    }
}
