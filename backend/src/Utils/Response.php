<?php

namespace App\Utils;

use App\System\HttpStatus;

class Response
{
    private static bool $modoTeste = false;

    private static int $statusAtual = HttpStatus::OK;

    public static function ativarModoTeste(): void
    {
        self::$modoTeste = true;
    }

    public static function desativarModoTeste(): void
    {
        self::$modoTeste = false;
    }

    public function json(mixed $data, int $status = HttpStatus::OK): void
    {
        self::enviarCabecalho($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        self::finalizar();
    }

    public static function noContent(): void
    {
        self::enviarCabecalho(HttpStatus::NO_CONTENT);
        self::finalizar();
    }

    public static function error(string $message, int $status = HttpStatus::BAD_REQUEST): void
    {
        self::enviarCabecalho($status);
        echo json_encode(['error' => $message], JSON_UNESCAPED_UNICODE);
        self::finalizar();
    }

    private static function enviarCabecalho(int $status): void
    {
        self::$statusAtual = $status;
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
    }

    private static function finalizar(): void
    {
        if (!self::$modoTeste) {
            exit;
        }
    }

    public static function getStatus(): int
    {
        return self::$statusAtual;
    }
}
