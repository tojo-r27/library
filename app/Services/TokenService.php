<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

class TokenService
{
    /**
     * Create an access token with expiration.
     */
    public function createAccessToken(User $user): NewAccessToken
    {
        $new = $user->createToken('access-token');
        $model = $new->accessToken;
        $model->expires_at = now()->addSeconds(config('sanctum.access_expiration', 1800));
        $model->save();
        return $new;
    }

    /**
     * Create a refresh token with expiration.
     */
    public function createRefreshToken(User $user): NewAccessToken
    {
        $new = $user->createToken('refresh-token');
        $model = $new->accessToken;
        $model->expires_at = now()->addSeconds(config('sanctum.refresh_expiration', 86400));
        $model->save();
        return $new;
    }

    /**
     * Validate a raw token string is a non-expired refresh token and return model.
     */
    public function validateRefreshToken(string $plainToken): ?PersonalAccessToken
    {
        /** @var PersonalAccessToken|null $token */
        $token = PersonalAccessToken::findToken($plainToken);
        if (!$token) {
            return null;
        }
        if ($token->name !== 'refresh-token') {
            return null;
        }
        if ($token->expires_at && $token->expires_at->isPast()) {
            return null;
        }
        return $token;
    }
}
