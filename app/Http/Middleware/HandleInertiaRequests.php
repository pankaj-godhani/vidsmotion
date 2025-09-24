<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Payment;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $activeSubscription = null;

        if ($user) {
            // Check for any completed subscription that hasn't expired yet
            // This allows access even if the subscription was deactivated early
            $activeSubscription = Payment::where('user_id', $user->id)
                ->where('status', 'completed')
                ->where('subscription_end', '>', now())
                ->orderBy('subscription_end', 'desc') // Get the latest subscription
                ->first();
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
                'activeSubscription' => $activeSubscription,
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'csrf_token' => csrf_token(),
        ];
    }
}
