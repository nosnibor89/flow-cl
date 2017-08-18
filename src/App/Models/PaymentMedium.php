<?php

namespace App\Models;

/**
 * PaymentMedium Enum
 */
final class PaymentMedium
{
    const __default = self::Webpay;
    
    const Webpay = 1;
    const Servipag = 2;
    const Multicaja = 3;
}
