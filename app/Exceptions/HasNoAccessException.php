<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class HasNoAccessException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message ?: "Only owner can request this action", $code, $previous);
    }
}
