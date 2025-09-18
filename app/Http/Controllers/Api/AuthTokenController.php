<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthTokenController extends Controller
{
    /**
     * Create a new personal access token using email/password.
     */
    public function issueToken(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string|max:100',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $tokenName = $request->input('device_name', 'api');
        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'success' => true,
            'token_type' => 'Bearer',
            'access_token' => $token,
        ]);
    }
}


