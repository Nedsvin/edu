<?php

namespace App\Entities\Registration;

use App\Utils\Exceptions\InvalidRegistrationException;

class Validator
{
    public static function validate(INPUT $input): void
    {
        if ($input->getStudentId() <= 0 || $input->getClassId() <= 0) {
            throw new InvalidRegistrationException("Aluno ou turma inválidos.");
        }
    }
}
