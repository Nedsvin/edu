<?php

namespace App\Controller;

use App\System\BaseController;
use App\System\HttpStatus;
use App\Entities\Student\Service;
use App\Entities\Student\INPUT;
use App\Utils\Pagination;
use App\Utils\Response;

class StudentController extends BaseController
{

    public function __construct(
        private readonly Service $service,
        private readonly Response $response
    ) {}

    public function createNew(array $data): void
    {
        $input = INPUT::fromArray($data);
        $this->service->createNew($input);

        $this->response->json(['message' => 'Aluno cadastrado.'], HttpStatus::CREATED);
    }

    public function getWithPages(array $params): void
    {
        $paramsFilter = Pagination::getParams($params);
        $res = $this->service->getAll($paramsFilter, 'name:asc');

        $result = Pagination::formatResponse( $res['data'], $res['total'] );

        $this->response->json($result, HttpStatus::OK);
    }

    public function getId(int $id): void
    {
        $student = $this->service->getById($id);
        $this->response->json($student, HttpStatus::OK);
    }

    public function getByName(array $data): void
    {
        $name = $data['name'] ?? '';
        $students = $this->service->getByName($name);
        $this->response->json($students, HttpStatus::OK);
    }

    public function update(int $id, array $data): void
    {
        $input = INPUT::fromArray($data);
        $this->service->update($id, $input);

        $this->response->json(['message' => 'Aluno atualizado.'], HttpStatus::OK);
    }

    public function remove(int $id): void
    {
        $this->service->remove($id);
        $this->response->json(['message' => 'Aluno removido.'], HttpStatus::OK);
    }
}
