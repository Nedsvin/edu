<?php

namespace App\Entities\Class;

class INPUT
{
    private string $name;
    private string $description;

    private function __construct() {}

    public static function fromArray(array $data): self
    {
        $input = new self();
        $input->name = trim($data['name'] ?? '');
        $input->description = trim($data['description'] ?? '');

        return $input;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
