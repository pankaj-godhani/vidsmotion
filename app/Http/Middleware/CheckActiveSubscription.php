<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            Log::info('CheckActiveSubscription: User not authenticated, redirecting to login');
            return redirect()->route('login');
        }

        $user = Auth::user();
        Log::info('CheckActiveSubscription: User authenticated', ['user_id' => $user->id, 'user_name' => $user->name]);

        // Check if user has any completed subscription that hasn't expired yet
        // This allows access even if the subscription was deactivated early
        $activeSubscription = Payment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('subscription_end', '>', now())
            ->orderBy('subscription_end', 'desc') // Get the latest subscription
            ->first();

        if (!$activeSubscription) {
            // No active subscription: zero out credits
            if ($user->credits > 0) {
                $previous = $user->credits;
                $user->update(['credits' => 0]);
                Log::info('CheckActiveSubscription: Subscription expired, resetting credits to zero', [
                    'user_id' => $user->id,
                    'previous_credits' => $previous
                ]);
            }

            Log::info('CheckActiveSubscription: No active subscription found, redirecting to pricing', ['user_id' => $user->id]);
            return redirect()->route('pricing')->with('error', 'You need an active subscription to access the video generator. Please subscribe to a plan first.');
        }

        Log::info('CheckActiveSubscription: Active subscription found, allowing access', [
            'user_id' => $user->id,
            'subscription_id' => $activeSubscription->id,
            'plan_name' => $activeSubscription->plan_name,
            'expires' => $activeSubscription->subscription_end
        ]);

        return $next($request);
    }
}
