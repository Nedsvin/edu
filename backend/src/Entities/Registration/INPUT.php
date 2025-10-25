<?php

namespace App\Entities\Registration;

class INPUT
{
    private int $student_id;
    private int $class_id;

    private function __construct() {}

    public static function fromArray(array $data): self
    {
        $input = new self();
        $input->student_id = (int)($data['student_id'] ?? 0);
        $input->class_id = (int)($data['class_id'] ?? 0);
        return $input;
    }

    public function getStudentId(): int
    {
        return $this->student_id;
    }

    public function getClassId(): int
    {
        return $this->class_id;
    }
}
