<?php

namespace App\Exception;

use Throwable;

class CitiesAlreadyExistsException extends \Exception
{
    public function __construct(string $message = "Cities table is not empty!", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
