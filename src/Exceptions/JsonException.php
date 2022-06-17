<?php

namespace PhpStudy\Src\Exceptions;

use Exception;
use Throwable;

class JsonException extends Exception
{
    public function __construct(string $message = "", int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}