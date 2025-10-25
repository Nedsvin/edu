<?php

namespace App\Entities\Class;

use App\Utils\Exceptions\InvalidClassException;

class Validator
{
    public static function validate(INPUT $input): void
    {
        if (strlen(trim($input->getName())) < 3) {
            throw new InvalidClassException("O Nome da turma deve ter no mínimo 3 caracteres.");
        }

        if (empty($input->getDescription())) {
            throw new InvalidClassException("A descrição é obrigatória.");
        }
    }
}
