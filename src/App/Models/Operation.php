<?php

namespace App\Models;

/**
 * Operation Enum
 */
final class Operation
{
    const __default = self::Payment;
    
    const Payment = 1;
    const Failure = 2;
    const Success = 3;
    const Confirmation = 4;
    const Return = 5;
}
