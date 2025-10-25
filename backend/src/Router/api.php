<?php

use App\Controller\StudentController;
use App\Controller\AuthController;
use App\Controller\ClassController;
use App\Controller\RegistrationController;
use App\Controller\UserController;

$router->post('/login', [AuthController::class, 'login']);
$router->get('/me',     [AuthController::class, 'me']);

$router->group('/alunos', function($r) {
    $r->post('',         [StudentController::class, 'createNew']);
    $r->get('',          [StudentController::class, 'getWithPages']);
    $r->get('/busca',    [StudentController::class, 'getByName']);
    $r->get('/{id}',     [StudentController::class, 'getId']);
    $r->put('/{id}',     [StudentController::class, 'update']);
    $r->delete('/{id}',  [StudentController::class, 'remove']);
});

$router->group('/turmas', function($r) {
    $r->post('',         [ClassController::class, 'createNew']);
    $r->get('',          [ClassController::class, 'getWithPages']);
    $r->get('/busca',    [ClassController::class, 'getByName']);
    $r->get('/{id}',     [ClassController::class, 'getId']);
    $r->put('/{id}',     [ClassController::class, 'update']);
    $r->delete('/{id}',  [ClassController::class, 'remove']);
});

$router->group('/matriculas', function($r) {
    $r->post('',               [RegistrationController::class, 'registration']);
    $r->get('',                [RegistrationController::class, 'getWithPages']);
    $r->get('/turma/{id}',     [RegistrationController::class, 'getAllWithClass']);
    $r->delete('',             [RegistrationController::class, 'remove']);
});

$router->group('/usuarios', function($r) {
    $r->post('',         [UserController::class, 'createNew']);
    $r->get('',          [UserController::class, 'getWithPages']);
    $r->get('/busca',    [UserController::class, 'getByName']);
    $r->get('/{id}',     [UserController::class, 'getId']);
    $r->put('/{id}',     [UserController::class, 'update']);
    $r->delete('/{id}',  [UserController::class, 'remove']);
});
