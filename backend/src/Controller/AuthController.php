<?php

namespace App\Controller;

use App\System\BaseController;
use App\System\HttpStatus;
use App\Entities\User\Service;
use App\Utils\Response;
use App\System\TokenManager;

class AuthController extends BaseController
{

    public function __construct(
        private readonly Service $service,
        private readonly TokenManager $tokenManager,
        private readonly Response $response
    ) {}

    public function me(): void
    {
        $user = $this->authUser();
        $this->response->json([
            'id'    => $user->getId(),
            'name'  => $user->getName(),
            'email' => $user->getEmail(),
            'role' => $user->getRole()
        ]);
    }

    public function login(array $data): void
    {
        try {
            $user = $this->service->auth($data['email'], $data['password']);

            $token = $this->tokenManager->generate([
                'id'    => $user->getId(),
                'email' => $user->getEmail(),
                'role' => $user->getRole()
            ]);

            $this->response->json([
                'token'   => $token,
                'user' => [
                    'id'    => $user->getId(),
                    'name'  => $user->getName(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole()
                ]
            ], HttpStatus::OK);
        } catch (\Exception $e) {
            $this->response->json(['error' => $e->getMessage()], HttpStatus::UNAUTHORIZED);
        }
    }
}
