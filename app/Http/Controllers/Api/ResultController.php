<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResultController extends Controller
{
    public function result(Request $request, $id): JsonResponse
    {
        /** @var \App\Models\Upload|null $upload */
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

    /**
     * Stream a completed upload file for download
     */
    public function download(Request $request, $id)
    {
        /** @var \App\Models\Upload|null $upload */
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
                'message' => 'File not ready for download',
            ], 400);
        }

        // If local file is missing but we have a remote URL, fall back to redirecting
        $metadata = is_array($upload->metadata)
            ? $upload->metadata
            : (is_string($upload->metadata) ? (json_decode($upload->metadata, true) ?: []) : []);

        $remoteUrl = $metadata['video_url']
            ?? (is_array($upload->result_data) ? ($upload->result_data['video_url'] ?? null) : null);

        if (empty($upload->file_path) || !Storage::disk('public')->exists($upload->file_path)) {
            if ($remoteUrl) {
                return redirect()->away($remoteUrl);
            }

            return response()->json([
                'success' => false,
                'message' => 'File not found on server and no remote URL available',
            ], 404);
        }

        // Local file exists, stream it for download

        $filename = $upload->original_filename ?: basename($upload->file_path);

        return Storage::disk('public')->download($upload->file_path, $filename, [
            'Content-Type' => $upload->file_type ?: 'application/octet-stream',
        ]);
    }
}
