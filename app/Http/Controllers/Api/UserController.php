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
        $userId = auth()->id();

        $stats = [
            'totalUploads' => Upload::where('user_id', $userId)->count(),
            'completedUploads' => Upload::where('user_id', $userId)->where('status', 'completed')->count(),
            'processingUploads' => Upload::where('user_id', $userId)->whereIn('status', ['pending', 'processing'])->count(),
            'failedUploads' => Upload::where('user_id', $userId)->where('status', 'failed')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
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
}
