<?php

namespace App\Entities\User;

use App\System\BaseRepository;
use PDO;

/**
 * Implementação do repositório de usuários, responsável por interações com a table "users".
 *
 * @package App\Entities\User
 */
class RepositoryImpl extends BaseRepository implements Repository
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->table = 'users';
    }

    public function createNew(Entity $user): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (name, email, password, role)
            VALUES (:name, :email, :password, :role)
        ");

        $stmt->execute([
            ':name'  => $user->getName(),
            ':email' => $user->getEmail(),
            ':password' => $user->getPassword(),
            ':role' => $user->getRole()
        ]);
    }

    public function getEmail(string $email): ?Entity
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Entity(
            $row['name'],
            $row['email'],
            $row['password'],
            (int) $row['id'],
            $row['role']
        );
    }

    public function hasEmail(string $email, ?int $dumpId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";

        if ($dumpId) {
            $sql .= " AND id != :id";
        }

        $stmt = $this->pdo->prepare($sql);
        $params = [
            ':email' => $email
        ];

        if ($dumpId) {
            $params[':id'] = $dumpId;
        }

        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    protected function entityMap(array $data): object
    {
        return new Entity(
            $data['name'],
            $data['email'],
            $data['password'],
            (int) $data['id'],
            $data['role']
        );
    }
}
