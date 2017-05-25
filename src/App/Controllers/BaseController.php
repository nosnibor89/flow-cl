<?php

namespace App\Controllers;

/**
*   Every controller extending this class have access shared resources
*/
class BaseController
{
    protected $paymentService;
	
	public function __construct($container)
    {
		$this->paymentService = $container->get('PaymentService');
	}
}
