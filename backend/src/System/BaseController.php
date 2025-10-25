<?php

namespace App\System;

use App\System\Exceptions\UnauthorizedException;
use App\Entities\User\Entity;
use App\Entities\User\Repository;

class BaseController
{
    private ?Entity $user = null;

    private TokenManager $tokenManager;
    private Repository $userRepository;

    public function __construct(?TokenManager $tokenManager = null, ?Repository $userRepository = null)
    {
        $this->tokenManager = $tokenManager ?? new TokenManager();
        $this->userRepository = $userRepository ?? new \App\Entities\User\RepositoryImpl(Database::connect());
    }

    protected function json(array $data, int $status = HttpStatus::OK): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    protected function success(string $message = 'Operação realizada com sucesso'): void
    {
        $this->json(['message' => $message]);
    }

    protected function error(string $message, int $status = HttpStatus::BAD_REQUEST): void
    {
        $this->json(['error' => $message], $status);
    }

    protected function authUser(): Entity
    {
        if ($this->user) {
            return $this->user;
        }

        $headers = \getallheaders();
        $authorization = $headers['Authorization'] ?? '';

        if (!str_starts_with($authorization, 'Bearer ')) {
            throw new UnauthorizedException('Token ausente ou inválido.');
        }

        $token = trim(str_replace('Bearer', '', $authorization));
        $data = $this->tokenManager->validate($token);

        if (!$data || !isset($data['id'])) {
            throw new UnauthorizedException('Token inválido.');
        }

        $user = $this->userRepository->getById($data['id']);

        if (!$user) {
            throw new UnauthorizedException('Usuário não encontrado.');
        }

        return $this->user = $user;
    }
}
