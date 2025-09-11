<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StatusController extends Controller
{
    public function status(Request $request, $id): JsonResponse
    {
        $upload = Upload::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$upload) {
            return response()->json([
                'success' => false,
                'message' => 'Upload not found or access denied',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $upload->id,
                'filename' => $upload->original_filename,
                'status' => $upload->status,
                'uploaded_at' => $upload->created_at,
                'processed_at' => $upload->processed_at,
                'error_message' => $upload->error_message,
            ]
        ]);
    }
}
