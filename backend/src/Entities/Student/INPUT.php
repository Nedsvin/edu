<?php

namespace App\Entities\Student;

use App\Utils\DateHelper;
use App\Utils\Normalizer;

class INPUT
{
    private string $name;
    private string $birth;
    private string $cpf;
    private string $email;
    private string $password;

    private function __construct() {}

    public static function fromArray(array $data): self
    {
        $input = new self();
        $input->name       = trim($data['name'] ?? '');
        $input->birth = DateHelper::formatDate($data['birth'] ?? '');
        $input->cpf        = Normalizer::cpf($data['cpf'] ?? '');
        $input->email      = trim($data['email'] ?? '');
        $input->password      = trim($data['password'] ?? '');

        return $input;
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
}
