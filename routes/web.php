<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

// Features Route
Route::get('/features', function () {
    return Inertia::render('Features', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('features');

// Pricing Route
Route::get('/pricing', function () {
    $user = Auth::user();
    $hasActiveSubscription = false;
    $activeSubscription = null;

    if ($user) {
        // Check if user has an active subscription
        $activeSubscription = \App\Models\Payment::forUser($user->id)
            ->where('is_active', true)
            ->where('subscription_end', '>', now())
            ->first();

        $hasActiveSubscription = $activeSubscription ? true : false;
    }

    return Inertia::render('Pricing', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'hasActiveSubscription' => $hasActiveSubscription,
        'activeSubscription' => $activeSubscription,
    ]);
})->name('pricing');

// Explore Route
Route::get('/explore', function () {
    return Inertia::render('Explore', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('explore');

// Terms & Conditions Route
Route::get('/terms', function () {
    return Inertia::render('Terms', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('terms');

// Contact Us Route
Route::get('/contact', function () {
    return Inertia::render('Contact', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('contact');

// Privacy Policy Route
Route::get('/privacy', function () {
    return Inertia::render('Privacy', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('privacy');

// FAQ Route
Route::get('/faq', function () {
    return Inertia::render('FAQ', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('faq');

// Cookie Settings Route
Route::get('/cookie-settings', function () {
    return Inertia::render('CookieSettings', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('cookie-settings');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Video Generator Route
Route::get('/video-generator', function () {
    return Inertia::render('VideoGenerator');
})->middleware(['auth', 'verified'])->name('video-generator');

// User Dashboard Routes
Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('User/Dashboard');
    })->name('dashboard');

    Route::get('/uploads/{id}', function ($id) {
        return Inertia::render('User/UploadDetails', ['uploadId' => $id]);
    })->name('uploads.show');
});

// Admin Dashboard Routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');

    Route::get('/users/{id}', function ($id) {
        return Inertia::render('Admin/UserDetails', ['userId' => $id]);
    })->name('users.show');

    Route::get('/uploads/{id}', function ($id) {
        return Inertia::render('Admin/UploadDetails', ['uploadId' => $id]);
    })->name('uploads.show');
});

// Payment routes (moved from api.php for CSRF compatibility)
Route::post('/api/create-razorpay-order', [App\Http\Controllers\PaymentController::class, 'createRazorpayOrder'])->name('payment.create-order');
Route::post('/api/payment-success', [App\Http\Controllers\PaymentController::class, 'handlePaymentSuccess'])->name('payment.success');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // My Profile page
    Route::get('/my-profile', [MyProfileController::class, 'index'])->name('my-profile');
        Route::post('/subscription/deactivate', [MyProfileController::class, 'deactivateSubscription'])->name('subscription.deactivate');
        Route::post('/profile/avatar', [MyProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

    // User payment routes
    Route::get('/api/user/payments', [App\Http\Controllers\PaymentController::class, 'getUserPayments'])->name('payment.user-payments');
    Route::get('/api/user/active-subscription', [App\Http\Controllers\PaymentController::class, 'getActiveSubscription'])->name('payment.active-subscription');
});

require __DIR__.'/auth.php';
