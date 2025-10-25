<?php

namespace App\Controller;

use App\System\BaseController;
use App\System\HttpStatus;
use App\Entities\Registration\INPUT;
use App\Entities\Registration\Service;
use App\Utils\Pagination;
use App\Utils\Response;

class RegistrationController extends BaseController
{
    public function __construct(
        private readonly Service $service,
        private readonly Response $response
    ) {}


    public function registration(array $data): void
    {
        $input = INPUT::fromArray($data);
        $this->service->registration($input);

        $this->response->json(['message' => 'Matrícula realizada.'], HttpStatus::CREATED);
    }

    public function getWithPages(array $params): void
    {
        $paramsFilter = Pagination::getParams($params);
        $res = $this->service->getAll($paramsFilter, 'date_registration:desc');

        $result = Pagination::formatResponse( $res['data'], $res['total']);

        $this->response->json($result, HttpStatus::OK);
    }

    public function getAllWithClass(int $classId): void
    {
        $students = $this->service->getAllWithClass($classId);
        $this->response->json($students, HttpStatus::OK);
    }

    public function remove(array $data): void
    {
        $input = INPUT::fromArray($data);
        $this->service->remove($input);

        $this->response->json(['message' => 'Matrícula removida.'], HttpStatus::OK);
    }
}
