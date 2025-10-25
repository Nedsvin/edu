<?php

namespace App\Entities\User;

interface Repository
{
    public function createNew(Entity $user): void;

    public function getAll(array $params, string $ordem = 'name:asc', ?array $allowedFields = null): array;

    public function getById(int $id): ?object;

    public function getEmail(string $email): ?Entity;

    public function hasEmail(string $email, ?int $dumpId = null): bool;

    public function update(int $id, array $data): void;

    public function remove(int $id): void;
}
