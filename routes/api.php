<?php

use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\ResultController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\StripCsrfFromApi;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful as SanctumStateful;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Token issuance (password to PAT) for Swagger and external clients
Route::middleware([StripCsrfFromApi::class])->post('/auth/token', [App\Http\Controllers\Api\AuthTokenController::class, 'issueToken'])
    ->name('api.auth.token');

// Public auth endpoints (stateless, no CSRF/stateful sanctum)
Route::middleware([StripCsrfFromApi::class])->post('/auth/register', [App\Http\Controllers\Api\AuthController::class, 'register'])
    ->withoutMiddleware([SanctumStateful::class])
    ->name('api.auth.register');
Route::middleware([StripCsrfFromApi::class])->post('/auth/login', [App\Http\Controllers\Api\AuthController::class, 'login'])
    ->withoutMiddleware([SanctumStateful::class])
    ->name('api.auth.login');
Route::middleware([StripCsrfFromApi::class])->post('/auth/forgot-password', [App\Http\Controllers\Api\AuthController::class, 'forgotPassword'])
    ->withoutMiddleware([SanctumStateful::class])
    ->name('api.auth.forgot');
Route::middleware([StripCsrfFromApi::class])->post('/auth/reset-password', [App\Http\Controllers\Api\AuthController::class, 'resetPassword'])
    ->withoutMiddleware([SanctumStateful::class])
    ->name('api.auth.reset');
Route::middleware([StripCsrfFromApi::class,'auth:sanctum'])->group(function () {
    Route::post('/auth/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->name('api.auth.logout');
    Route::post('/auth/change-password', [App\Http\Controllers\Api\AuthController::class, 'changePassword'])->name('api.auth.change');
    Route::put('/user/profiles', [App\Http\Controllers\Api\AuthController::class, 'updateProfile'])->name('api.user.profile');
});

// Payment routes moved to web.php for CSRF compatibility

// File processing routes moved to web.php for session-based authentication

// Image upload route (using Sanctum authentication)
Route::post('/upload-image', [App\Http\Controllers\Api\UploadController::class, 'uploadImage'])->middleware([StripCsrfFromApi::class, 'auth:sanctum'])->name('api.upload-image');

// Swagger removed

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

    // My Files: list completed videos with download URLs
    Route::get('/my-files', [App\Http\Controllers\Api\UserController::class, 'myFiles'])->name('api.user.my-files');
    // My Files stats
    Route::get('/my-files/stats', [App\Http\Controllers\Api\UserController::class, 'myFilesStats'])->name('api.user.my-files.stats');
});

// Public Explore API
Route::middleware([StripCsrfFromApi::class])->get('/explore', [App\Http\Controllers\Api\ExploreController::class, 'index'])
    ->name('api.explore.index');

// Explore Videos API
Route::middleware([StripCsrfFromApi::class])->group(function () {
    // Public routes
    Route::get('/explore-videos', [App\Http\Controllers\Api\ExploreVideoController::class, 'index'])->name('api.explore-videos.index');
    Route::get('/explore-videos/{id}', [App\Http\Controllers\Api\ExploreVideoController::class, 'show'])->name('api.explore-videos.show');
    Route::post('/explore-videos/{id}/like', [App\Http\Controllers\Api\ExploreVideoController::class, 'toggleLike'])->name('api.explore-videos.like');
});

// Authenticated routes
Route::middleware([StripCsrfFromApi::class, 'auth:sanctum'])->group(function () {
    Route::post('/explore-videos', [App\Http\Controllers\Api\ExploreVideoController::class, 'store'])->name('api.explore-videos.store');
    Route::put('/explore-videos/{id}', [App\Http\Controllers\Api\ExploreVideoController::class, 'update'])->name('api.explore-videos.update');
    Route::delete('/explore-videos/{id}', [App\Http\Controllers\Api\ExploreVideoController::class, 'destroy'])->name('api.explore-videos.destroy');
    Route::get('/my-explore-videos', [App\Http\Controllers\Api\ExploreVideoController::class, 'myVideos'])->name('api.explore-videos.my-videos');
});

// Payment API routes (no CSRF, API-style)
Route::middleware([StripCsrfFromApi::class])->group(function () {
    Route::post('/create-razorpay-order', [App\Http\Controllers\PaymentController::class, 'createRazorpayOrder'])->name('payment.create-order');
    Route::post('/create-subscription', [App\Http\Controllers\PaymentController::class, 'createRazorpaySubscription'])->name('payment.create-subscription');
});
