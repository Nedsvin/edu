<?php

namespace App\Utils;

class Logger
{
    private const LOG_FILE = __DIR__ . '/../../storage/logs/error.log';

    public static function erro(string|\Throwable $erro): void
    {
        $message = is_string($erro) ? self::formatStringError($erro) : self::formatThrowable($erro);

        file_put_contents(self::LOG_FILE, $message . PHP_EOL, FILE_APPEND);
    }

    private static function formatStringError(string $erro): string
    {
        $now = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
        return "[$now] $erro";
    }

    private static function formatThrowable(\Throwable $erro): string
    {
        $now = (new \DateTimeImmutable())->format('Y-m-d H:i:s');

        return sprintf(
            "[%s] %s em %s:%d\nTrace: %s",
            $now,
            $erro->getMessage(),
            $erro->getFile(),
            $erro->getLine(),
            $erro->getTraceAsString()
        );
    }
}
