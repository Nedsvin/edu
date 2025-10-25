<?php

namespace App\Controller;

use App\System\BaseController;
use App\System\HttpStatus;
use App\Entities\Class\INPUT;
use App\Entities\Class\Service;
use App\Utils\Pagination;
use App\Utils\Response;

class ClassController extends BaseController
{

    public function __construct(
        private readonly Service $service,
        private readonly Response $response
    ) {}

    public function getByName(string $name): void
    {
        $classes = $this->service->getByName($name);
        $this->response->json($classes, HttpStatus::OK);
    }

    public function getId(int $id): void
    {
        $class = $this->service->getById($id);
        $this->response->json($class, HttpStatus::OK);
    }

    public function createNew(array $data): void
    {
        $input = INPUT::fromArray($data);
        $this->service->createNew($input);

        $this->response->json(['message' => 'Turma cadastrada.'], HttpStatus::CREATED);
    }

    public function getWithPages(array $params): void
    {
        $paramsFilter = Pagination::getParams($params);
        $res = $this->service->getAll($paramsFilter, 'name:asc');

        $result = Pagination::formatResponse( $res['data'], $res['total']);

        $this->response->json($result, HttpStatus::OK);
    }

    public function update(int $id, array $data): void
    {
        $input = INPUT::fromArray($data);
        $this->service->update($id, $input);

        $this->response->json(['message' => 'Turma atualizada.'], HttpStatus::OK);
    }

    public function remove(int $id): void
    {
        $this->service->remove($id);
        $this->response->json(['message' => 'Turma removida.'], HttpStatus::OK);
    }
}
