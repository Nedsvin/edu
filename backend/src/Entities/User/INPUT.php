<?php

namespace App\Entities\User;

class INPUT
{
    private string $name;

    private string $email;

    private string $password;

    private string $papel;

    private function __construct() {}

    public static function fromArray(array $data): self
    {
        $input = new self();
        $input->name    = trim($data['name'] ?? '');
        $input->email   = trim($data['email'] ?? '');
        $input->password   = trim($data['password'] ?? '');
        $input->papel   = trim($data['papel'] ?? '');

        return $input;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPapel(): string
    {
        return $this->papel;
    }
}
