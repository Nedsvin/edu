<?php

namespace App\Entities\class;

use App\Utils\Exceptions\ExistingClassException;
use App\System\Exceptions\NotFoundException;

class Service
{
    public function __construct(
        private Repository $repository
    ) {}

    public function createNew(INPUT $input): void
    {
        Validator::validate($input);

        if ($this->repository->withSameName($input->getName())) {
            throw new ExistingClassException("Turma '{$input->getName()}' já existe.");
        }

        $entity = new Entity(
            $input->getName(),
            $input->getDescription()
        );

        $this->repository->createNew($entity);
    }

    public function getAll(array $params, string $ordem = 'name:asc'): array
    {
        return $this->repository->getAll($params, $ordem, Filter::allowedFields());
    }

    public function getById(int $id): Entity
    {
        $entity = $this->repository->getById($id);
        if (!$entity) {
            throw new NotFoundException("Turma {$id} não encontrada.");
        }

        return $entity;
    }

    public function getByName(string $name): array
    {
        return $this->repository->getByName($name);
    }

    public function update(int $id, INPUT $data): void
    {
        Validator::validate($data);

        $entity = $this->repository->getById($id);
        if (!$entity) {
            throw new NotFoundException("Turma {$id} não encontrada.");
        }

        if ($this->repository->withSameName($data->getName(), $id)) {
            throw new ExistingClassException("Turma '{$data->getName()}' já existe.");
        }

        $entity->setName($data->getName());
        $entity->setDescription($data->getDescription());

        $this->repository->update($id, [
            'name'       => $entity->getName(),
            'description'  => $entity->getDescription(),
        ]);
    }

    public function remove(int $id): void
    {
        $entity = $this->repository->getById($id);
        if (!$entity) {
            throw new NotFoundException("Turma {$id} não encontrada.");
        }

        $this->repository->remove($id);
    }
}
