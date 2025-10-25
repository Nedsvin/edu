<?php

namespace App\Utils;

class Logger
{
    public static function erro(string|\Throwable $erro): void
    {
        $now = date('Y-m-d H:i:s');

        $message = is_string($erro)
            ? "[$now] $erro"
            : sprintf(
                "[%s] %s em %s:%d\nTrace: %s\n",
                $now,
                $erro->getMessage(),
                $erro->getFile(),
                $erro->getLine(),
                $erro->getTraceAsString()
            );

        file_put_contents(
            __DIR__ . '/../../storage/logs/error.log',
            $message . "\n",
            FILE_APPEND
        );
    }
}
