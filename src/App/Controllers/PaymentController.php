<?php

namespace App\Controllers;

class PaymentController extends BaseController
{
	
	public function getConfig($request, $response, $args) {
		
	}
	
	public function pay($request, $response, $args) {
		$data = array('Example' => 'This is an exmple');
		// 		$paymentService = $this->paymentService;
		print_r( $this->paymentService->getConfig());
		return $response->withJson($data);
		
	}


	public function failed(){

	}

	public function success(){

	}

}
