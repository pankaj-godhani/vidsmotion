<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;

// Admin routes (custom adminpanel.* names to avoid conflicts with existing admin.*)
Route::prefix('admin')->group(function () {
    // Login routes
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('adminpanel.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('adminpanel.login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('adminpanel.logout');

    // Protected admin routes
    Route::middleware(['admin.session'])->group(function () {
        Route::get('/panel', [AdminAuthController::class, 'dashboard'])->name('adminpanel.dashboard');
        Route::get('/users', [AdminAuthController::class, 'users'])->name('adminpanel.users');
        // Use non-conflicting paths to avoid collision with existing /admin/users/{id}
        Route::get('/users/filter/active', [AdminAuthController::class, 'activeUsers'])->name('adminpanel.users.active');
        Route::get('/users/filter/inactive', [AdminAuthController::class, 'inactiveUsers'])->name('adminpanel.users.inactive');
        Route::get('/plans', [AdminAuthController::class, 'plans'])->name('adminpanel.plans');
        Route::get('/payments', [AdminAuthController::class, 'payments'])->name('adminpanel.payments');
        
        // Profile routes
        Route::get('/profile', [AdminAuthController::class, 'profile'])->name('adminpanel.profile');
        Route::post('/profile', [AdminAuthController::class, 'updateProfile'])->name('adminpanel.profile.update');
        Route::get('/change-password', [AdminAuthController::class, 'changePassword'])->name('adminpanel.change-password');
        Route::post('/change-password', [AdminAuthController::class, 'updatePassword'])->name('adminpanel.password.update');

        // Users JSON + actions
        // Users JSON + actions (use non-conflicting paths)
        Route::get('/users-json/{id}', [AdminAuthController::class, 'getUserJson'])->name('adminpanel.users.json');
        Route::post('/users-update/{id}', [AdminAuthController::class, 'updateUser'])->name('adminpanel.users.update');
        Route::delete('/users-delete/{id}', [AdminAuthController::class, 'deleteUser'])->name('adminpanel.users.delete');
        Route::post('/users-change-password/{id}', [AdminAuthController::class, 'changeUserPassword'])->name('adminpanel.users.change-password');
    });
});
