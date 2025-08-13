<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register a new user and issue an access token.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
            ],
        ], 201);
    }

    /**
     * Log the user in and issue an access token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials',
                ], 401);
            }

            /** @var User $user */
            $user = Auth::user();

            // Create access & refresh tokens with expirations
            $accessTokenResult = $user->createToken('access-token');
            $accessToken = $accessTokenResult->plainTextToken;
            $accessTokenModel = $accessTokenResult->accessToken;
            $accessTokenModel->expires_at = now()->addSeconds(config('sanctum.access_expiration', 1800));
            $accessTokenModel->save();

            $refreshTokenResult = $user->createToken('refresh-token');
            $refreshToken = $refreshTokenResult->plainTextToken;
            $refreshTokenModel = $refreshTokenResult->accessToken;
            $refreshTokenModel->expires_at = now()->addSeconds(config('sanctum.refresh_expiration', 86400));
            $refreshTokenModel->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Welcome library',
                'data' => [
                    'accessToken' => $accessToken,
                    'refreshToken' => $refreshToken,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    /**
     * Log the user out (revoke the token).
     */
    public function logout(): JsonResponse
    {
        $user = Auth::user();
        try {
            if ($user && $user->currentAccessToken()) {
                $user->currentAccessToken()->delete();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Logged out successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
            ], 500);
        }
    }
}
