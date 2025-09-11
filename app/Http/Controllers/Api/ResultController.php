<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ResultController extends Controller
{
    public function result(Request $request, $id): JsonResponse
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

        if ($upload->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'File processing is not completed yet',
                'data' => [
                    'status' => $upload->status,
                ]
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $upload->id,
                'filename' => $upload->original_filename,
                'status' => $upload->status,
                'result_data' => $upload->result_data,
                'processed_at' => $upload->processed_at,
            ]
        ]);
    }
}
