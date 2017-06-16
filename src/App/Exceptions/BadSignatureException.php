<?php

namespace App\Flow\Exceptions;

class BadSignatureException extends FlowException
{
      public function __construct($message = "Bad signature for Flow.cl", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
