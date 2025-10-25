<?php

namespace App\Entities\Class;

interface Repository
{

    public function createNew(Entity $turma): void;

    public function getAll(array $params, string $ordem = 'name:asc', ?array $allowedFields = null): array;

    public function getById(int $id): ?object;

    public function getByName(string $name): array;

    public function withSameName(string $name, ?int $dumpId = null): bool;

    public function update(int $id, array $data): void;

    public function remove(int $id): void;
}
