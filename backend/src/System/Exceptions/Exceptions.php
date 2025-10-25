<?php
declare(strict_types=1);

namespace App\System\Exceptions;

use App\System\HttpStatus;
use Exception;
use Throwable;

class HttpException extends Exception {}

class BadRequestException extends HttpException
{
    public function __construct(string $message = 'Bad Request')
    {
        parent::__construct($message, HttpStatus::BAD_REQUEST);
    }
}

class NotFoundException extends HttpException
{
    public function __construct(string $message = 'Not Found')
    {
        parent::__construct($message, HttpStatus::NOT_FOUND);
    }
}

class UnauthorizedException extends HttpException
{
    public function __construct(string $message = "Acesso não autorizado", int $code = HttpStatus::UNAUTHORIZED)
    {
        parent::__construct($message, $code);
    }
}

class DatabaseConnectionException extends Exception
{
    protected array $context;

    public function __construct(
        string $message = 'Erro ao conectar ao banco de dados.',
        int $code = HttpStatus::INTERNAL_SERVER_ERROR,
        ?Throwable $previous = null,
        array $context = []
    ) {
        $this->context = $context;
        parent::__construct($message, $code, $previous);
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
