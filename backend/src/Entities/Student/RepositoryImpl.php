<?php

namespace App\Entities\Student;

use App\System\BaseRepository;
use PDO;

/**
 * Implementação concreta do repositório de students.
 */
class RepositoryImpl extends BaseRepository implements Repository
{

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->table = 'students';
    }

    public function createNew(Entity $student): void
    {
        $sql = "INSERT INTO {$this->table} (name, birth, cpf, email, password)
                VALUES (:name, :birth, :cpf, :email, :password)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'name'       => $student->getName(),
            'birth' => $student->getBirth(),
            'cpf'        => $student->getCpf(),
            'email'      => $student->getEmail(),
            'password'      => $student->getPassword()
        ]);
    }

    public function emailOuCpfExiste(string $email, string $cpf, ?int $dumpId = null): bool
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE (email = :email OR cpf = :cpf)";

        if ($dumpId) {
            $sql .= " AND id != :id";
        }

        $stmt = $this->pdo->prepare($sql);

        $params = [
            'email' => $email,
            'cpf' => $cpf
        ];

        if ($dumpId) {
            $params['id'] = $dumpId;
        }

        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'] > 0;
    }

    public function getByName(string $name): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE name LIKE :name ORDER BY name ASC");
        $stmt->execute(['name' => "%$name%"]);

        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => $this->entityMap($row), $registros);
    }

    protected function entityMap(array $data): object
    {
        return new Entity(
            $data['name'],
            $data['birth'],
            $data['cpf'],
            $data['email'],
            $data['password'],
            (int) $data['id']
        );
    }
}
