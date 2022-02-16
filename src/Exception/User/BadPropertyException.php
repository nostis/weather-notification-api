<?php

namespace App\Exception\User;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BadPropertyException extends HttpException
{
    public function __construct(int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR, ?string $message = 'Dynamic property not found', \Throwable $previous = null, array $headers = [], ?int $code = 0)
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}