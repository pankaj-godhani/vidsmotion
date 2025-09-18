<?php

use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\ResultController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Token issuance (password to PAT) for Swagger and external clients
Route::post('/auth/token', [App\Http\Controllers\Api\AuthTokenController::class, 'issueToken'])->name('api.auth.token');

// Auth endpoints
Route::post('/auth/register', [App\Http\Controllers\Api\AuthController::class, 'register'])->name('api.auth.register');
Route::post('/auth/login', [App\Http\Controllers\Api\AuthController::class, 'login'])->name('api.auth.login');
Route::post('/auth/forgot-password', [App\Http\Controllers\Api\AuthController::class, 'forgotPassword'])->name('api.auth.forgot');
Route::post('/auth/reset-password', [App\Http\Controllers\Api\AuthController::class, 'resetPassword'])->name('api.auth.reset');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->name('api.auth.logout');
    Route::post('/auth/change-password', [App\Http\Controllers\Api\AuthController::class, 'changePassword'])->name('api.auth.change');
    Route::put('/user/profile', [App\Http\Controllers\Api\AuthController::class, 'updateProfile'])->name('api.user.profile');
});

// Payment routes moved to web.php for CSRF compatibility

// File processing routes moved to web.php for session-based authentication

// Image upload route (using Sanctum authentication)
Route::post('/upload-image', [App\Http\Controllers\Api\UploadController::class, 'uploadImage'])->middleware('auth:sanctum')->name('api.upload-image');

// Swagger UI and JSON via L5 Swagger controller
Route::get('/docs', [\L5Swagger\Http\Controllers\SwaggerController::class, 'api'])->name('api.docs');
Route::get('/docs/json', [\L5Swagger\Http\Controllers\SwaggerController::class, 'docs'])->name('api.docs.json');

// Webhook routes (no authentication required)
Route::post('/webhook/piapi', [App\Http\Controllers\Api\PiapiWebhookController::class, 'handleWebhook'])->name('api.webhook.piapi');

// Admin dashboard routes
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/stats', [App\Http\Controllers\Api\AdminController::class, 'stats'])->name('api.admin.stats');
        Route::get('/activity', [App\Http\Controllers\Api\AdminController::class, 'activity'])->name('api.admin.activity');
        Route::get('/users', [App\Http\Controllers\Api\AdminController::class, 'users'])->name('api.admin.users');
        Route::get('/uploads', [App\Http\Controllers\Api\AdminController::class, 'uploads'])->name('api.admin.uploads');
        Route::patch('/users/{id}/toggle-status', [App\Http\Controllers\Api\AdminController::class, 'toggleUserStatus'])->name('api.admin.users.toggle-status');
        Route::post('/uploads/{id}/retry', [App\Http\Controllers\Api\AdminController::class, 'retryUpload'])->name('api.admin.uploads.retry');
    });
});
