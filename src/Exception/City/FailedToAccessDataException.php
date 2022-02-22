<?php

namespace App\Exception;

use Throwable;

class FailedToAccessDataException extends \Exception
{
    public function __construct(string $message = "Failed to access data from converted file", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
