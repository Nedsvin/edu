<?php

namespace App\System;

use Dotenv\Dotenv;

class EnvLoader
{
    public static function load(string $path = null, string $filename = '.env'): void
    {
        $path = $path ?? __DIR__ . '/../../';
        $dotenv = Dotenv::createImmutable($path, $filename);
        $dotenv->load();
    }
}
