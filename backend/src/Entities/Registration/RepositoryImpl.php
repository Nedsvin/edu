<?php

namespace App\Entities\Registration;

use App\System\QueryBuilderHelper;
use PDO;

class RepositoryImpl implements Repository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function registration(Entity $registration): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO registrations (student_id, class_id, date_registration)
            VALUES (:student_id, :class_id, :data)
        ");

        $stmt->execute([
            ':student_id' => $registration->getStudentId(),
            ':class_id' => $registration->getClassId(),
            ':data'     => $registration->getDateRegistration() ?: date('Y-m-d')
        ]);
    }

    public function studentRegistration(int $studentId, int $classId): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM registrations
            WHERE student_id = :student AND class_id = :class
        ");

        $stmt->execute([
            ':student' => $studentId,
            ':class' => $classId
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function getAll(array $params, string $ordem = 'date_registration:desc'): array
    {
        $sql = "
            SELECT a.name AS student_name, a.cpf AS student_cpf, t.name AS class_name, m.*
            FROM registrations m
            JOIN students a ON m.student_id = a.id
            JOIN classes t ON m.class_id = t.id
        ";

        return QueryBuilderHelper::pageResponse(
            $this->pdo,
            $sql,
            $params,
            Filter::allowedFields(),
            $ordem,
            'm',
            function ($row) {
                $entidade = $this->entityMap($row);

                return [
                    'student_id' => $entidade->getStudentId(),
                    'student_name' => $row['student_name'],
                    'student_cpf' => $row['student_cpf'],

                    'class_id' => $entidade->getClassId(),
                    'class_name' => $row['class_name'],

                    'date_registration' => $entidade->getDateRegistration(),
                ];
            }
        );
    }


    public function getAllWithClass(int $classId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT a.name, a.email, a.cpf
            FROM registrations m
            JOIN students a ON m.student_id = a.id
            WHERE m.class_id = :class
            ORDER BY a.name ASC
        ");

        $stmt->execute([':class' => $classId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function remove(Entity $registration): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM registrations
            WHERE student_id = :student_id AND class_id = :class_id
        ");
        $stmt->execute([
            ':student_id' => $registration->getStudentId(),
            ':class_id' => $registration->getClassId(),
        ]);
    }

    protected function entityMap(array $row): Entity
    {
        if (is_null($row['student_id']) || is_null($row['class_id'])) {
            throw new \RuntimeException('student_id ou class_id está nulo.');
        }

        return new Entity(
            (int) $row['student_id'],
            (int) $row['class_id'],
            $row['date_registration'] ?? null,
            isset($row['id']) ? (int) $row['id'] : null
        );
    }
}
