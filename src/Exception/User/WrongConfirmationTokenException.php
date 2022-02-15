<?php

namespace App\Exception\User;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class WrongConfirmationTokenException extends HttpException
{
    public function __construct(int $statusCode = Response::HTTP_BAD_REQUEST, ?string $message = 'Wrong confirmation token', \Throwable $previous = null, array $headers = [], ?int $code = 0)
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}