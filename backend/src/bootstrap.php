<?php

use App\System\ControllerFactory;
use App\System\Database;
use App\Utils\Response;
use App\System\Router;
use App\System\TokenManager;

require_once __DIR__ . '/../vendor/autoload.php';

App\System\EnvLoader::load(__DIR__ . '/../');

$pdo = Database::connect();

$response = new Response();
$tokenManager = new TokenManager();

$controllerFactory = new ControllerFactory($pdo, $response, $tokenManager);

return new Router($controllerFactory);
