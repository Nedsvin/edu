<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

ob_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\System\EnvLoader;
use App\System\HttpStatus;
use App\System\AuthMiddleware;
use App\System\Exceptions\HttpException;
use App\System\Exceptions\NotFoundException;
use App\Utils\Response;
use App\Utils\Http;
use App\Utils\Logger;

EnvLoader::load(__DIR__ . '/../');

$router = require_once __DIR__ . '/../src/bootstrap.php';

require_once __DIR__ . '/../src/Router/api.php';

$rotaAtual = Http::getNormalizedUri();
$rotasPublicas = ['/login', '/health', '/docs', '/coverage-report'];

if ($rotaAtual === '/docs') {
    require_once __DIR__ . '/../public/docs/index.html';
    exit;
}

if ($rotaAtual === '/coverage-report') {
    require_once __DIR__ . '/../coverage-report/index.html';
    exit;
}

set_exception_handler(function (Throwable $e) {
    Logger::erro($e);
    Response::error('Erro interno.', HttpStatus::INTERNAL_SERVER_ERROR);
});

register_shutdown_function(function () {
    $erro = error_get_last();

    if ($erro && in_array($erro['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        Logger::erro($erro['message'] . " em {$erro['file']}:{$erro['line']}");
        Response::error('Erro inesperado.', HttpStatus::INTERNAL_SERVER_ERROR);
        exit;
    }
});

try {
    if (!in_array($rotaAtual, $rotasPublicas)) {
        AuthMiddleware::protect(getallheaders());
    }

    if (!$router->dispatch()) {
        throw new NotFoundException();
    }
} catch (HttpException $e) {
    Logger::erro($e);
    Response::error($e->getMessage(), $e->getCode() ?: HttpStatus::BAD_REQUEST);
} catch (Throwable $e) {
    Logger::erro($e);
    $error = [
        'message' => 'Erro inesperado.',
        'exception' => get_class($e),
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString(),
    ];

    Response::error(json_encode($error, JSON_PRETTY_PRINT), HttpStatus::INTERNAL_SERVER_ERROR);
} finally {
    ob_end_flush();
}
