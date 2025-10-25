<?php

namespace App\Entities\Registration;

use App\Utils\Exceptions\ExistingRegistrationException;
use App\System\Exceptions\NotFoundException;

class Service
{
    public function __construct(
        private readonly repository $repository
    ) {}

    public function registration(INPUT $input): void
    {
        Validator::validate($input);

        if ($this->repository->studentRegistration($input->getStudentId(), $input->getClassId())) {
            throw new ExistingRegistrationException("Aluno já matriculado nesta turma.");
        }

        $registration = new Entity(
            $input->getStudentId(),
            $input->getClassId(),
            date('Y-m-d')
        );

        $this->repository->registration($registration);
    }

    public function getAll(array $params, string $ordem = 'name:asc'): array
    {
        return $this->repository->getAll($params, $ordem, Filter::allowedFields());
    }

    public function getAllWithClass(int $classId): array
    {
        return $this->repository->getAllWithClass($classId);
    }

    public function remove(INPUT $input): void
    {
        Validator::validate($input);

        if (!$this->repository->studentRegistration($input->getStudentId(), $input->getClassId())) {
            throw new NotFoundException("Matrícula não encontrada.");
        }

        $registration = new Entity(
            $input->getStudentId(),
            $input->getClassId(),
        );

        $this->repository->remove($registration);
    }
}
