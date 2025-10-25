<?php

namespace App\Entities\Registration;

class Entity implements \JsonSerializable
{

    public function __construct(
        private int $studentId,
        private int $classId,
        private ?string $dateRegistration = null,
        private ?int $id = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudentId(): int
    {
        return $this->studentId;
    }

    public function getClassId(): int
    {
        return $this->classId;
    }

    public function getDateRegistration(): ?string
    {
        return $this->dateRegistration;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setStudentId(int $studentId): void
    {
        $this->studentId = $studentId;
    }

    public function setClassId(int $classId): void
    {
        $this->classId = $classId;
    }

    public function setDateRegistration(string $dateRegistration): void
    {
        $this->dateRegistration = $dateRegistration;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->studentId,
            'class_id' => $this->classId,
            'date_registration' => $this->dateRegistration,
        ];
    }
}
