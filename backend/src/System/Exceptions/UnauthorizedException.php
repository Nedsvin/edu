<?php

namespace App\System\Exceptions;

use App\System\HttpStatus;

class UnauthorizedException extends HttpException
{
    public function __construct(string $message = "Acesso não autorizado", int $code = HttpStatus::UNAUTHORIZED)
    {
        parent::__construct($message, $code);
    }
}
