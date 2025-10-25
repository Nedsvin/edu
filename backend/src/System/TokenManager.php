<?php

namespace App\System;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use UnexpectedValueException;

const SESSION_DURATION = 3600;

class TokenManager
{
    private static ?string $secret = null;

    private static function loadSecret(): void
    {
        if (self::$secret === null) {
            $secret = $_ENV['JWT_SECRET'] ?? getenv('JWT_SECRET');
            if (!$secret) {
                throw new UnexpectedValueException('Secret não configurado');
            }
            self::$secret = $secret;
        }
    }

    public function generate(array $payload, int $expire = SESSION_DURATION): string
    {
        self::loadSecret();

        $now = time();
        $data = array_merge($payload, [
            'iat' => $now,
            'exp' => $now + $expire,
        ]);

        return JWT::encode($data, self::$secret, 'HS256');
    }

    public static function validate(string $token): ?array
    {
        try {
            self::loadSecret();
            $decoded = JWT::decode($token, new Key(self::$secret, 'HS256'));
            return (array) $decoded;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
