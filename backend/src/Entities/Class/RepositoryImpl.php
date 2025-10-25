<?php

namespace App\Entities\Class;

use App\System\BaseRepository;
use App\System\QueryBuilderHelper;
use PDO;

class RepositoryImpl extends BaseRepository implements Repository
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->table = 'classes';
    }

    public function createNew(Entity $class): void
    {
        $sql = "INSERT INTO {$this->table} (name, description) VALUES (:name, :description)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'name'      => $class->getName(),
            'description' => $class->getDescription(),
        ]);
    }

    public function getAll(array $params, string $ordem = 'name:asc', ?array $allowedFields = null): array
    {
        $sql = "
            SELECT t.*, COUNT(m.student_id) AS total_students
            FROM classes t
            LEFT JOIN registrations m ON m.class_id = t.id
        ";

        $countSql = "
            SELECT COUNT(DISTINCT t.id) AS total
            FROM classes t
            LEFT JOIN registrations m ON m.class_id = t.id
        ";

        return QueryBuilderHelper::pageResponse(
            $this->pdo,
            $sql,
            $params,
            Filter::allowedFields(),
            $ordem,
            't',
            fn($row) => $this->entityMap($row),
            $countSql,
            "t.id"
        );
    }

    public function getByName(string $name): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE name LIKE :name ORDER BY name ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => "%{$name}%"]);

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->entityMap($row);
        }

        return $result;
    }

    public function withSameName(string $name, ?int $dumpId = null): bool
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE name = :name";
        $params = ['name' => $name];

        if ($dumpId !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $dumpId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $row['total'] > 0;
    }

    public function update(int $id, array $data): void
    {
        $sql = "UPDATE {$this->table} SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id'        => $id,
            'name'      => $data['name'],
            'description' => $data['description'],
        ]);
    }

    public function remove(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function count(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM {$this->table}");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $row['total'];
    }

    protected function entityMap(array $row): object
    {
        return new Entity(
            $row['name'],
            $row['description'],
            $row['id'],
            $row['total_students'] ?? 0
        );
    }
}
