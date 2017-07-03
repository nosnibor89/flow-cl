<?php

namespace App\Exceptions;

class TokenException extends \Exception
{
    public function __construct($message = 'Could not generate token', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
