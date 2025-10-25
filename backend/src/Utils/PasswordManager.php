<?php

namespace App\Utils;

class PasswordManager
{

    public static function passwordHash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function password_verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public static function strongPassword(string $password): bool
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
    }
}
