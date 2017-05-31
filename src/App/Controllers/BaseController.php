<?php

namespace App\Controllers;

use \Interop\Container\ContainerInterface;

/**
*   Every controller extending this class have access shared resources
*/
class BaseController
{
    protected $paymentService;
    
    public function __construct(ContainerInterface $container)
    {
        $this->paymentService = $container->get('PaymentService');
        $this->validationService = $container->get('ValidationService');
    }
}
