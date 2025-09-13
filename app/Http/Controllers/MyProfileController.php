<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Payment;
use App\Models\User;
use Inertia\Inertia;

class MyProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Get user's payment history
        $payments = Payment::forUser($user->id)
            ->completed()
            ->orderBy('created_at', 'desc')
            ->get();

        // Get user's active or most recent subscription (including deactivated)
        $activeSubscription = Payment::forUser($user->id)
            ->where(function($query) {
                $query->where('is_active', true)
                      ->where('subscription_end', '>', now());
            })
            ->orWhere(function($query) {
                $query->where('is_active', false)
                      ->where('subscription_end', '>', now()); // Show deactivated but still within paid period
            })
            ->orderBy('created_at', 'desc')
            ->first();

        return Inertia::render('MyProfile', [
            'canLogin' => true,
            'payments' => $payments,
            'activeSubscription' => $activeSubscription,
        ]);
    }

    public function deactivateSubscription(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Find the user's active subscription
        $activeSubscription = Payment::forUser($user->id)
            ->where('is_active', true)
            ->where('subscription_end', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$activeSubscription) {
            return back()->with('error', 'No active subscription found.');
        }

        // Update the subscription to deactivated
        // Keep the original subscription_end date as user paid for the full period
        $activeSubscription->update([
            'is_active' => false,
            // subscription_end remains unchanged - user paid for the full period
        ]);

        return back()->with('success', 'Your subscription has been deactivated successfully.');
    }

    public function toggleAutoRenew(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Log the request for debugging
        Log::info('Auto-renew toggle request', [
            'user_id' => $user->id,
            'request_data' => $request->all(),
            'auto_renew_value' => $request->auto_renew,
            'auto_renew_type' => gettype($request->auto_renew)
        ]);

        $request->validate([
            'auto_renew' => 'required|boolean',
        ]);

        // Find the user's active subscription
        $activeSubscription = Payment::forUser($user->id)
            ->where('is_active', true)
            ->where('subscription_end', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$activeSubscription) {
            Log::info('No active subscription found for auto-renew toggle', ['user_id' => $user->id]);
            return back()->with('error', 'No active subscription found.');
        }

        // Update the auto-renew setting
        $activeSubscription->update([
            'auto_renew' => $request->auto_renew,
        ]);

        Log::info('Auto-renew setting updated successfully', [
            'subscription_id' => $activeSubscription->id,
            'auto_renew' => $request->auto_renew
        ]);

        $message = $request->auto_renew
            ? 'Auto-renewal has been enabled for your subscription.'
            : 'Auto-renewal has been disabled for your subscription.';

        return back()->with('success', $message);
    }

    public function updateAvatar(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        try {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            // Store new avatar
            $file = $request->file('avatar');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('avatars', $filename, 'public');

            // Update user's avatar
            $user->update(['avatar' => $filename]);

            return back()->with('success', 'Profile picture updated successfully!');

        } catch (\Exception $e) {
            Log::error('Avatar upload error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update profile picture. Please try again.');
        }
    }
}
