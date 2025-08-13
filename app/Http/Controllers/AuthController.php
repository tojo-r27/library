<?php

namespace App\Http\Controllers;

use App\Services\TokenService;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private TokenService $tokens)
    {
    }
    /**
     * Register a new user and issue an access token.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $user->email_verified_at = now();
        $user->save();

        // issue tokens
        $accessToken = $this->tokens->createAccessToken($user)->plainTextToken;
        $refreshToken = $this->tokens->createRefreshToken($user)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [],
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

            // Create access & refresh tokens via service
            $accessToken = $this->tokens->createAccessToken($user)->plainTextToken;
            $refreshToken = $this->tokens->createRefreshToken($user)->plainTextToken;

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
     * Refresh tokens using a valid refresh token.
     */
    public function refresh(Request $request): JsonResponse
    {
        $refreshToken = $request->get('refresh_token');
        if (!$refreshToken) {
            return response()->json([
                'status' => 'error',
                'message' => 'Refresh token required',
            ], 422);
        }

        $tokenModel = $this->tokens->validateRefreshToken($refreshToken);
        if (!$tokenModel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired refresh token',
            ], 401);
        }

        /** @var User $user */
        $user = $tokenModel->tokenable;
        // revoke old refresh token
        $tokenModel->delete();

        $accessToken = $this->tokens->createAccessToken($user)->plainTextToken;
        $newRefreshToken = $this->tokens->createRefreshToken($user)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'data' => [
                'accessToken' => $accessToken,
                'refreshToken' => $newRefreshToken,
            ],
        ]);
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
