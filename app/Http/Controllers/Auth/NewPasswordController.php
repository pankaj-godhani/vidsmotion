<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {
            // Log the request for debugging
            \Log::info('Password reset request', [
                'email' => $request->email,
                'has_token' => !empty($request->token),
                'csrf_token' => $request->header('X-CSRF-TOKEN'),
                'session_id' => session()->getId(),
            ]);

            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Password reset validation failed', [
                'errors' => $e->errors(),
                'email' => $request->email,
            ]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Password reset error', [
                'message' => $e->getMessage(),
                'email' => $request->email,
            ]);
            throw $e;
        }

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // Log the password reset status
        \Log::info('Password reset status', [
            'status' => $status,
            'email' => $request->email,
            'is_success' => $status == Password::PASSWORD_RESET,
        ]);

        // If the password was successfully reset, we will redirect the user to
        // a success page with their email. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status == Password::PASSWORD_RESET) {
            // Store email in session for the success page
            session(['reset_email' => $request->email]);

            return redirect()->route('password.reset.success');
        }

        // Handle different error statuses
        $errorMessage = trans($status);
        \Log::error('Password reset failed', [
            'status' => $status,
            'error_message' => $errorMessage,
            'email' => $request->email,
        ]);

        // Return validation errors that will be displayed on the form
        return back()->withErrors([
            'email' => $errorMessage,
        ]);
    }
}
