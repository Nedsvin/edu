<?php

namespace App\System;

use App\Controller\StudentController;
use App\Controller\AuthController;
use App\Controller\ExampleController;
use App\Controller\ClassController;
use App\Controller\RegistrationController;
use App\Controller\UserController;

use App\Entities\Student\RepositoryImpl as StudentRepository;
use App\Entities\Student\Service as StudentService;

use App\Entities\Class\RepositoryImpl as ClassRepository;
use App\Entities\Class\Service as ClassService;
use App\Entities\Class\Validator as ClassValidator;

use App\Entities\Registration\RepositoryImpl as RegistrationRepository;
use App\Entities\Registration\Service as RegistrationService;

use App\Entities\User\RepositoryImpl as UserRepository;
use App\Entities\User\Service as UserService;
use App\Entities\User\Validator as UserValidator;

use App\Utils\Response;
use App\System\TokenManager;

/**
 * Instancia os controladores com suas dependências.
 */
class ControllerFactory
{
    public function __construct(
        private readonly \PDO $pdo,
        private readonly Response $response,
        private readonly TokenManager $tokenManager
    ) {}

    public function make(string $controllerClass): object
    {
        return match ($controllerClass) {
            StudentController::class => new StudentController(
                new StudentService(
                    new StudentRepository($this->pdo)
                ),
                $this->response
            ),

            ClassController::class => new ClassController(
                new ClassService(
                    new ClassRepository($this->pdo),
                    new ClassValidator()
                ),
                $this->response
            ),

            RegistrationController::class => new RegistrationController(
                new RegistrationService(
                    new RegistrationRepository($this->pdo)
                ),
                $this->response
            ),

            AuthController::class => new AuthController(
                new UserService(
                    new UserRepository($this->pdo),
                    new UserValidator()
                ),
                $this->tokenManager,
                $this->response
            ),

            UserController::class => new UserController(
                new UserService(
                    new UserRepository($this->pdo),
                    new UserValidator()
                ),
                $this->response
            ),

            default => throw new \RuntimeException("Controller não existe: $controllerClass")
        };
    }
}
