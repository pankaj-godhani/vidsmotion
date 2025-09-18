<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *   path="/auth/register",
     *   summary="Register a new user",
     *   tags={"Auth"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"name","email","password","password_confirmation"},
     *       @OA\Property(property="name", type="string", example="John Doe"),
     *       @OA\Property(property="email", type="string", example="john@example.com"),
     *       @OA\Property(property="password", type="string", example="secret123"),
     *       @OA\Property(property="password_confirmation", type="string", example="secret123")
     *     )
     *   ),
     *   @OA\Response(response=201, description="Registered"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registered successfully',
            'token_type' => 'Bearer',
            'access_token' => $token,
        ], 201);
    }

    /**
     * @OA\Post(
     *   path="/auth/login",
     *   summary="Login and get access token",
     *   tags={"Auth"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", example="john@example.com"),
     *       @OA\Property(property="password", type="string", example="secret123")
     *     )
     *   ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'success' => true,
            'token_type' => 'Bearer',
            'access_token' => $token,
        ]);
    }

    /**
     * @OA\Post(
     *   path="/auth/logout",
     *   summary="Logout (revoke current token)",
     *   tags={"Auth"},
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="Logged out")
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();
        return response()->json(['success' => true, 'message' => 'Logged out']);
    }

    /**
     * @OA\Post(
     *   path="/auth/forgot-password",
     *   summary="Send password reset link",
     *   tags={"Auth"},
     *   @OA\RequestBody(@OA\JsonContent(@OA\Property(property="email", type="string", example="john@example.com"))),
     *   @OA\Response(response=200, description="Reset link sent or throttled")
     * )
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return response()->json([
            'success' => in_array($status, [Password::RESET_LINK_SENT, Password::PASSWORD_THROTTLED], true),
            'status' => $status,
        ], 200);
    }

    /**
     * @OA\Post(
     *   path="/auth/reset-password",
     *   summary="Reset password",
     *   tags={"Auth"},
     *   @OA\RequestBody(
     *     @OA\JsonContent(
     *       required={"token","email","password","password_confirmation"},
     *       @OA\Property(property="token", type="string"),
     *       @OA\Property(property="email", type="string"),
     *       @OA\Property(property="password", type="string"),
     *       @OA\Property(property="password_confirmation", type="string")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Password reset")
     * )
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
                $user->tokens()->delete();
            }
        );

        return response()->json([
            'success' => $status === Password::PASSWORD_RESET,
            'status' => $status,
        ], $status === Password::PASSWORD_RESET ? 200 : 422);
    }

    /**
     * @OA\Post(
     *   path="/auth/change-password",
     *   summary="Change password (authenticated)",
     *   tags={"Auth"},
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     @OA\JsonContent(
     *       required={"current_password","password","password_confirmation"},
     *       @OA\Property(property="current_password", type="string"),
     *       @OA\Property(property="password", type="string"),
     *       @OA\Property(property="password_confirmation", type="string")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Changed"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $user = $request->user();
        if (!$user || !Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(['success' => false, 'message' => 'Current password is incorrect'], 422);
        }

        $user->update(['password' => Hash::make($request->input('password'))]);
        // Invalidate other tokens if desired
        return response()->json(['success' => true, 'message' => 'Password changed']);
    }

    /**
     * @OA\Put(
     *   path="/user/profile",
     *   summary="Update profile (authenticated)",
     *   tags={"User"},
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="John Doe")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Updated")
     * )
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
        ]);
        $user = $request->user();
        $user->fill($data);
        $user->save();
        return response()->json(['success' => true, 'data' => ['name' => $user->name]]);
    }
}


