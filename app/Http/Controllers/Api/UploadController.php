<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:102400', // 100MB max
        ]);

        $file = $request->file('file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('uploads', $filename, 'public');

        $upload = Upload::create([
            'user_id' => auth()->id(),
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'status' => 'pending',
        ]);

        // Here you would typically dispatch a job to process the file
        // ProcessFileJob::dispatch($upload);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'data' => [
                'id' => $upload->id,
                'filename' => $upload->original_filename,
                'status' => $upload->status,
                'uploaded_at' => $upload->created_at,
            ]
        ], 201);
    }
}
