<?php

namespace App\Entities\Registration;

interface Repository
{
    public function registration(Entity $registration): void;

    public function getAll(array $params, string $ordem): array;

    public function getAllWithClass(int $classId): array;

    public function studentRegistration(int $studentId, int $classId): bool;

    public function remove(Entity $registration): void;
}
