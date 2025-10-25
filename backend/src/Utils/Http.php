<?php

namespace App\Utils;

class Http
{
    public static function getNormalizedUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (str_starts_with($uri, '/api')) {
            $uri = substr($uri, 4) ?: '/';
        }

        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }

        return $uri;
    }
}
