<?php
declare(strict_types=1);

namespace App\Utils\Exceptions;

use App\System\Exceptions\BadRequestException;

class InvalidUserException extends BadRequestException
{
    public function __construct(string $message = "Usuário inválido.", int $codigo = 0)
    {
        parent::__construct($message, $codigo);
    }
}

class ExistingUserException extends BadRequestException
{
    public function __construct(string $message = "Usuário já cadastrado.", int $codigo = 0)
    {
        parent::__construct($message, $codigo);
    }
}

class InvalidStudentException extends BadRequestException
{
    public function __construct(string $message = "Aluno inválido.", int $codigo = 0)
    {
        parent::__construct($message, $codigo);
    }
}

class ExistingStudentException extends BadRequestException
{
    public function __construct(string $message = "Aluno já cadastrado com esse email ou CPF.", int $codigo = 0)
    {
        parent::__construct($message, $codigo);
    }
}

class ExistingRegistrationException extends BadRequestException
{
    public function __construct(string $message = "Aluno já está matriculado nesta turma.", int $codigo = 0)
    {
        parent::__construct($message, $codigo);
    }
}

class InvalidRegistrationException extends BadRequestException
{
    public function __construct(string $message = "Matrícula inválida.", int $codigo = 0)
    {
        parent::__construct($message, $codigo);
    }
}

class InvalidClassException extends BadRequestException
{
    public function __construct(string $message = "Turma inválida.", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}

class ExistingClassException extends BadRequestException
{
    public function __construct(string $message = "Turma já existe.", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
