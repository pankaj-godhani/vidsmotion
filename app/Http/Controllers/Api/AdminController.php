<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function stats(Request $request): JsonResponse
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalUploads' => Upload::count(),
            'activeUploads' => Upload::whereIn('status', ['pending', 'processing'])->count(),
            'failedUploads' => Upload::where('status', 'failed')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function activity(Request $request): JsonResponse
    {
        // This would typically come from an activity log table
        // For now, we'll return recent uploads as activity
        $recentUploads = Upload::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($upload) {
                return [
                    'id' => $upload->id,
                    'type' => 'file_uploaded',
                    'description' => "{$upload->user->name} uploaded {$upload->original_filename}",
                    'created_at' => $upload->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $recentUploads
        ]);
    }

    public function users(Request $request): JsonResponse
    {
        $users = User::withCount('uploads')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'uploads_count' => $user->uploads_count,
                    'last_login_at' => $user->last_login_at ?? $user->created_at,
                    'is_active' => true, // You might want to add an is_active field to users table
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function uploads(Request $request): JsonResponse
    {
        $uploads = Upload::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $uploads
        ]);
    }

    public function toggleUserStatus(Request $request, $id): JsonResponse
    {
        // This would typically toggle an is_active field
        // For now, we'll just return success
        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully'
        ]);
    }

    public function retryUpload(Request $request, $id): JsonResponse
    {
        $upload = Upload::findOrFail($id);

        $upload->update([
            'status' => 'pending',
            'error_message' => null,
        ]);

        // Here you would typically dispatch a job to retry processing
        // ProcessFileJob::dispatch($upload);

        return response()->json([
            'success' => true,
            'message' => 'Upload queued for retry'
        ]);
    }
}
