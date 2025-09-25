<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\SitemapController;
use App\Services\SeoService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

Route::get('/', function () {
    $seoData = SeoService::getPageSeo('home');

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'seo' => $seoData,
    ]);
})->name('welcome');

// Debug route to test CSRF token
Route::get('/debug-csrf', function () {
    return response()->json([
        'csrf_token' => csrf_token(),
        'session_id' => session()->getId(),
        'app_url' => config('app.url'),
        'session_domain' => config('session.domain'),
        'session_secure' => config('session.secure'),
        'session_same_site' => config('session.same_site'),
    ]);
});

// Features Route
Route::get('/features', function () {
    $seoData = SeoService::getPageSeo('features');

    return Inertia::render('Features', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'seo' => $seoData,
    ]);
})->name('features');

// Pricing Route
Route::get('/pricing', function () {
    $user = Auth::user();
    $hasActiveSubscription = false;
    $activeSubscription = null;

    if ($user) {
        // Check if user has an ACTIVE subscription that hasn't expired yet
        // Only consider subscriptions that are both active AND not expired
        $activeSubscription = \App\Models\Payment::forUser($user->id)
            ->where('status', 'completed')
            ->where('is_active', true) // Only active subscriptions
            ->where('subscription_end', '>', now())
            ->orderBy('subscription_end', 'desc') // Get the latest subscription
            ->first();

        $hasActiveSubscription = $activeSubscription ? true : false;
    }

    $seoData = SeoService::getPageSeo('pricing');

    return Inertia::render('Pricing', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'hasActiveSubscription' => $hasActiveSubscription,
        'activeSubscription' => $activeSubscription,
        'seo' => $seoData,
    ]);
})->name('pricing');

// Explore Route
Route::get('/explore', function () {
    $user = Auth::user();
    $hasActiveSubscription = false;
    $activeSubscription = null;

    if ($user) {
        $activeSubscription = \App\Models\Payment::forUser($user->id)
            ->where('status', 'completed')
            ->where('is_active', true)
            ->where('subscription_end', '>', now())
            ->orderBy('subscription_end', 'desc')
            ->first();

        $hasActiveSubscription = $activeSubscription ? true : false;
    }

    $seoData = SeoService::getPageSeo('explore');

    return Inertia::render('Explore', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'auth' => [
            'user' => $user
        ],
        'hasActiveSubscription' => $hasActiveSubscription,
        'activeSubscription' => $activeSubscription,
        'seo' => $seoData,
    ]);
})->name('explore');

// Terms & Conditions Route
Route::get('/terms', function () {
    $seoData = SeoService::getPageSeo('terms');

    return Inertia::render('Terms', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'seo' => $seoData,
    ]);
})->name('terms');

// Contact Us Route
Route::get('/contact', function () {
    $seoData = SeoService::getPageSeo('contact');

    return Inertia::render('Contact', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'seo' => $seoData,
    ]);
})->name('contact');

// Privacy Policy Route
Route::get('/privacy', function () {
    $seoData = SeoService::getPageSeo('privacy');

    return Inertia::render('Privacy', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'seo' => $seoData,
    ]);
})->name('privacy');

// FAQ Route
Route::get('/faq', function () {
    $seoData = SeoService::getPageSeo('faq');

    return Inertia::render('FAQ', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'seo' => $seoData,
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
Route::get('/video-generator', function (Request $request) {
    $recreateData = null;

    // Check if this is a recreate request
    if ($request->has('recreate') && $request->get('recreate') === 'true') {
        // Get recreate data from session storage (set by frontend)
        $recreateIntent = session()->get('recreate_intent');
        if ($recreateIntent) {
            $recreateData = json_decode($recreateIntent, true);
        }
    }

    return Inertia::render('VideoGenerator', [
        'recreateData' => $recreateData,
        'isRecreateMode' => $request->has('recreate') && $request->get('recreate') === 'true',
        'userCredits' => auth()->user()->credits ?? 0,
    ]);
})->middleware(['auth', 'verified', 'active.subscription'])->name('video-generator');

// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// User Dashboard Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-files', function () {
        return Inertia::render('User/Dashboard');
    })->name('my-files');

    Route::get('/uploads/{id}', function ($id) {
        return Inertia::render('User/UploadDetails', ['uploadId' => $id]);
    })->name('uploads.show');

    // Secure download route
    Route::get('/api/download/{id}', [App\Http\Controllers\Api\ResultController::class, 'download'])
        ->name('api.download');
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

// Payment routes (moved back to web.php for CSRF compatibility)
Route::post('/api/payment-success', [App\Http\Controllers\PaymentController::class, 'handlePaymentSuccess'])->name('payment.success');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // My Profile page
    Route::get('/my-profile', [MyProfileController::class, 'index'])->name('my-profile');
        Route::post('/subscription/deactivate', [MyProfileController::class, 'deactivateSubscription'])->name('subscription.deactivate');
        Route::post('/subscription/auto-renew', [MyProfileController::class, 'toggleAutoRenew'])->name('subscription.auto-renew');

// Razorpay webhook route (should be outside auth middleware)
Route::post('/razorpay/webhook', [App\Http\Controllers\RazorpayWebhookController::class, 'handleWebhook'])->name('razorpay.webhook');
        Route::post('/profile/avatar', [MyProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

    // User payment routes
    Route::get('/api/user/payments', [App\Http\Controllers\PaymentController::class, 'getUserPayments'])->name('payment.user-payments');
    Route::get('/api/user/active-subscription', [App\Http\Controllers\PaymentController::class, 'getActiveSubscription'])->name('payment.active-subscription');

    // User dashboard API routes
    Route::get('/api/user/stats', [App\Http\Controllers\Api\UserController::class, 'stats'])->name('api.user.stats');
    Route::get('/api/user/uploads', [App\Http\Controllers\Api\UserController::class, 'uploads'])->name('api.user.uploads');
    Route::get('/api/user/credits', [App\Http\Controllers\Api\UserController::class, 'credits'])->name('api.user.credits');

    // Video generation API routes
    Route::post('/api/upload', [App\Http\Controllers\Api\UploadController::class, 'upload'])->name('api.upload');
    Route::get('/api/status/{task_id}', [App\Http\Controllers\Api\StatusController::class, 'status'])->name('api.status');
    Route::get('/api/result/{id}', [App\Http\Controllers\Api\ResultController::class, 'result'])->name('api.result');
    Route::get('/api/video-proxy', [App\Http\Controllers\Api\VideoProxyController::class, 'proxy'])->name('api.video-proxy');

    // Test endpoint to verify authentication
    Route::get('/api/test-auth', function() {
        return response()->json([
            'success' => true,
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name ?? 'Unknown',
            'authenticated' => auth()->check()
        ]);
    })->name('api.test-auth');
});

require __DIR__.'/auth.php';
