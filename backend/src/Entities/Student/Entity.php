<?php

namespace App\Entities\Student;

class Entity implements \JsonSerializable
{
    public function __construct(
        private string $name,
        private string $birth,
        private string $cpf,
        private string $email,
        private string $password,
        private ?int $id = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBirth(): string
    {
        return $this->birth;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setBirth(string $birth): void
    {
        $this->birth = $birth;
    }

    public function setCpf(string $cpf): void
    {
        $this->cpf = $cpf;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'birth' => $this->birth,
            'cpf'        => $this->cpf,
            'email'      => $this->email
        ];
    }
}
