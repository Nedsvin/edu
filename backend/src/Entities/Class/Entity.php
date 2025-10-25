<?php

namespace App\Entities\Class;

class Entity implements \JsonSerializable
{
    private ?int $id;
    private string $name;
    private string $description;
    private ?int $totalStudents = null;

    public function __construct(string $name, string $description, ?int $id = null, ?int $totalStudents = 0)
    {
        $this->name = $name;
        $this->description = $description;
        $this->id = $id;
        $this->totalStudents = $totalStudents;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getTotalStudents(): ?int
    {
        return $this->totalStudents;
    }

    public function setTotalStudents(int $totalStudents): void
    {
        $this->totalStudents = $totalStudents;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'total_students' => $this->totalStudents
        ];
    }
}
