<?php

declare(strict_types=1);

namespace App\System;

use App\System\Exceptions\UnauthorizedException;

class AuthMiddleware
{
    private const BEARER_PREFIX = 'Bearer ';

    public static function protect(array $headers): void
    {
        $authHeader = $headers['Authorization'] ?? '';

        if (!self::hasBearerPrefix($authHeader)) {
            throw new UnauthorizedException('Token de autenticação ausente');
        }

        $token = self::extractToken($authHeader);
        $data  = TokenManager::validate($token);

        if (!$data) {
            throw new UnauthorizedException('Token inválido ou expirado');
        }

        $_REQUEST['auth'] = $data;
    }

    private static function hasBearerPrefix(string $header): bool
    {
        return str_starts_with($header, self::BEARER_PREFIX);
    }

    private static function extractToken(string $header): string
    {
        return trim(substr($header, strlen(self::BEARER_PREFIX)));
    }
}
