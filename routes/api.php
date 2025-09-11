<?php

use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\ResultController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Payment routes
Route::post('/create-razorpay-order', [PaymentController::class, 'createRazorpayOrder'])->name('api.create-razorpay-order');
Route::post('/payment-success', [PaymentController::class, 'handlePaymentSuccess'])->name('api.payment-success');

// API routes for file processing
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/upload', [UploadController::class, 'upload'])->name('api.upload');
    Route::get('/status/{id}', [StatusController::class, 'status'])->name('api.status');
    Route::get('/result/{id}', [ResultController::class, 'result'])->name('api.result');

    // User dashboard routes
    Route::prefix('user')->group(function () {
        Route::get('/stats', [App\Http\Controllers\Api\UserController::class, 'stats'])->name('api.user.stats');
        Route::get('/uploads', [App\Http\Controllers\Api\UserController::class, 'uploads'])->name('api.user.uploads');
    });

    // Admin dashboard routes
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/stats', [App\Http\Controllers\Api\AdminController::class, 'stats'])->name('api.admin.stats');
        Route::get('/activity', [App\Http\Controllers\Api\AdminController::class, 'activity'])->name('api.admin.activity');
        Route::get('/users', [App\Http\Controllers\Api\AdminController::class, 'users'])->name('api.admin.users');
        Route::get('/uploads', [App\Http\Controllers\Api\AdminController::class, 'uploads'])->name('api.admin.uploads');
        Route::patch('/users/{id}/toggle-status', [App\Http\Controllers\Api\AdminController::class, 'toggleUserStatus'])->name('api.admin.users.toggle-status');
        Route::post('/uploads/{id}/retry', [App\Http\Controllers\Api\AdminController::class, 'retryUpload'])->name('api.admin.uploads.retry');
    });
});
