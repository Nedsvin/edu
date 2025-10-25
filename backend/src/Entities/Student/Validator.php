<?php

namespace App\Entities\Student;

use App\Utils\Exceptions\InvalidStudentException;
use App\Utils\CpfValidator;
use App\Utils\PasswordManager;
use App\Utils\DateHelper;

class Validator
{
    public static function validate(INPUT $student, ?int $id): void
    {
        if (strlen($student->getName()) < 3) {
            throw new InvalidStudentException('Nome deve ter ao menos 3 caracteres.');
        }

        if (!filter_var($student->getEmail(), FILTER_VALIDATE_EMAIL)) {
            throw new InvalidStudentException('E-mail inválido.');
        }

        if (!CpfValidator::isValido($student->getCpf())) {
            throw new InvalidStudentException('CPF inválido.');
        }

        if (!PasswordManager::strongPassword($student->getPassword()) && !$id) {
            throw new InvalidStudentException('Senha fraca. Use letras maiúsculas, minúsculas, número e símbolo.');
        }

        if ($student->getPassword() && $id && !PasswordManager::strongPassword($student->getPassword())) {
            throw new InvalidStudentException('Senha fraca. Use letras maiúsculas, minúsculas, número e símbolo.');
        }

        if (!$student->getBirth() || !strtotime($student->getBirth()) || !DateHelper::birthDateNow($student->getBirth())) {
            throw new InvalidStudentException('Data de nascimento inválida ou idade menor que 17.');
        }

    }
}
