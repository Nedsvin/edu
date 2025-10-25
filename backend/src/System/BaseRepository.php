<?php

namespace App\System;

use PDO;
use PDOException;

abstract class BaseRepository
{
    protected PDO $pdo;

    protected string $table;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    abstract protected function entityMap(array $data): object;

    public function getAll(array $params, string $ordem = 'name:asc', ?array $allowedFields = null): array
    {
        $allowedFields = $allowedFields ?? ['name'];

        $sqlBase = "SELECT * FROM {$this->table} AS t";

        return QueryBuilderHelper::pageResponse(
            pdo: $this->pdo,
            sqlBase: $sqlBase,
            params: $params,
            allowedFields: $allowedFields,
            ordem: $ordem,
            alias: 't',
            map: fn($row) => $this->entityMap($row),
        );
    }

    public function getById(int $id): ?object
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $registro = $stmt->fetch(PDO::FETCH_ASSOC);

        return $registro ? $this->entityMap($registro) : null;
    }

    public function update(int $id, array $data): void
    {
        $fields = array_keys($data);
        $set = implode(', ', array_map(fn($field) => "$field = :$field", $fields));

        $data['id'] = $id;

        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET $set WHERE id = :id");
        $stmt->execute($data);
    }

    public function remove(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
