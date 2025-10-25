<?php

namespace App\Controller;

use App\System\BaseController;
use App\System\HttpStatus;
use App\Entities\User\Service;
use App\Entities\User\INPUT;
use App\Utils\Pagination;
use App\Utils\Response;

class UserController extends BaseController
{
    public function __construct(
        private readonly Service $service,
        private readonly Response $response
    ) {}

    public function createNew(array $data): void
    {
        $input = INPUT::fromArray($data);
        $this->service->createNew($input);
        $this->response->json(['message' => 'Usuário cadastrado.'], HttpStatus::CREATED);
    }

    public function getWithPages(array $params): void
    {
        $paramsFilter = Pagination::getParams($params);
        $retorno = $this->service->getAll($paramsFilter, 'name:asc');

        $result = Pagination::formatResponse($retorno['data'], $retorno['total']);
        $this->response->json($result, HttpStatus::OK);
    }

    public function getId(int $id): void
    {
        $user = $this->service->getById($id);
        $this->response->json($user, HttpStatus::OK);
    }

    public function getEmail(string $email): void
    {
        $user = $this->service->getEmail($email);
        $this->response->json($user, HttpStatus::OK);
    }

    public function update(int $id, array $data): void
    {
        $input = INPUT::fromArray($data);
        $this->service->update($id, $input);
        $this->response->json(['message' => 'Usuário atualizado.'], HttpStatus::OK);
    }

    public function remove(int $id): void
    {
        $this->service->remove($id);
        $this->response->json(['message' => 'Usuário removido.'], HttpStatus::OK);
    }
}
