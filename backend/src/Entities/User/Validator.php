<?php

namespace App\Entities\User;

use App\Utils\Exceptions\InvalidUserException;
use App\Utils\PasswordManager;

class Validator
{
    public static function validate(INPUT $input, ?int $id = null): void
    {
        if (strlen(trim($input->getName())) < 3) {
            throw new InvalidUserException("Nome deve ter ao menos 3 caracteres.");
        }

        if (!filter_var($input->getEmail(), FILTER_VALIDATE_EMAIL)) {
            throw new InvalidUserException("E-mail inválido.");
        }

        if (!PasswordManager::strongPassword($input->getPassword()) && !$id) {
            throw new InvalidUserException("Senha fraca. Use letras maiúsculas, minúsculas, números e símbolos.");
        }

        if ($id && $input->getPassword() && !PasswordManager::strongPassword($input->getPassword())) {
            throw new InvalidUserException("Senha inválida.");
        }

        if (!in_array($input->getRole(), ['admin', 'user'], true)) {
            throw new InvalidUserException("role inválido.");
        }
    }
}
