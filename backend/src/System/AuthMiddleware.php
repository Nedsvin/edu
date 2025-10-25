<?php

namespace App\System;

use App\System\Exceptions\UnauthorizedException;

class AuthMiddleware
{
    public static function protect(array $headers): void
    {
        $authHeader = $headers['Authorization'] ?? '';

        if (!str_starts_with($authHeader, 'Bearer ')) {
            throw new UnauthorizedException('Token de autenticação ausente');
        }

        $token = trim(str_replace('Bearer ', '', $authHeader));
        $data = TokenManager::validate($token);

        if (!$data) {
            throw new UnauthorizedException('Token inválido ou expirado');
        }

        $_REQUEST['auth'] = $data;
    }
}