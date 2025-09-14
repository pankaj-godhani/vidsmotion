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
        'isRecreateMode' => $request->has('recreate') && $request->get('recreate') === 'true'
    ]);
})->middleware(['auth', 'verified', 'active.subscription'])->name('video-generator');

// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

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
Route::post('/api/create-subscription', [App\Http\Controllers\PaymentController::class, 'createRazorpaySubscription'])->name('payment.create-subscription');
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
});

require __DIR__.'/auth.php';
