<?php

namespace App\Entities\Student;

interface Repository
{

    public function createNew(Entity $student): void;

    public function getAll(array $params, string $ordem, ?array $allowedFields): array;

    public function getById(int $id): ?object;

    public function getByName(string $name): array;

    public function emailOuCpfExiste(string $email, string $cpf, ?int $dumpId = null): bool;

    public function update(int $id, array $data): void;

    public function remove(int $id): void;
}
