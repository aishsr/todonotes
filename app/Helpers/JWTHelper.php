<?php

declare(strict_types = 1);

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Carbon\Carbon;

class JWTHelper
{
    /**
     * Decodes a JWT into an associated array
     *
     * @param string $jwt Authorization Bearer Token
     *
     * @return array|null
     */
    public static function decode($jwt): array|null
    {
        if (! is_null($jwt)) {
            // Verify the JWT or return error response

            if ('local' == config('app.env')) {
                // DEBUG: this should never be set in production
                $timestamp = config('scrawlr/jwt.timestamp');
                JWT::$timestamp = $timestamp;
            }
            $leeway = config('scrawlr/jwt.leeway');

            if ($leeway > 0) {
                JWT::$leeway = $leeway;
            }

            try {
                $decoded = JWT::decode($jwt, new Key(config('scrawlr/jwt.secret'), config('scrawlr/jwt.algo')));

                return (array) $decoded;
            } catch (\Throwable $th) {
                Log::warning('Invalid JWT Token', ['jwt' => $jwt, 'exception' => $th]);
            }
        }

        return null;
    }

    /**
     * Encodes a JWT from an associated array
     *
     * @param string $extraParameters Authorization Bearer Token
     *
     * @return array|null
     */
    public static function encode($extraParameters): array
    {
        $expires = Carbon::now()->addSeconds(config('scrawlr/jwt.ttl'));
        // Ref: https://datatracker.ietf.org/doc/html/rfc7519#section-4.1.1
        $payload = [
            'iss' => 'The iss (issuer) claim identifies the principal that issued the JWT',
            'aud' => 'The sub (subject) claim identifies the principal that is the subject of the JWT',
            // "iat" (issued at) claim identifies the time at which the JWT was issued
            'iat' => Carbon::now()->timestamp,
            // "exp" (expiration time) claim identifies the expiration time on or after which the JWT MUST NOT be accepted for processing.
            'exp' => $expires->timestamp,
            // "nbf" (not before) claim identifies the time before which the JWT MUST NOT be accepted for processing.
            // "nbf" => 1357000000
        ];

        if (is_array($extraParameters)) {
            $payload += $extraParameters;
        }

        $jwt = JWT::encode($payload, config('scrawlr/jwt.secret'), config('scrawlr/jwt.algo'));

        return [
            'access_token' => $jwt,
            'token_type' => 'bearer',
            'expires_in' => config('scrawlr/jwt.ttl'),
            'expires_at' => $expires->toIso8601String(),
        ];
    }
}
