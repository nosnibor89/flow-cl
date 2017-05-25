<?php

namespace App\Controllers;

class HomeController extends BaseController
{
	public function home($request, $response, $args) {
		$data = array('Example' => 'This is an exmple');
		// $paymentService = $this->paymentService;
		print_r( $this->paymentService->getConfig('.beelivery'));
		return $response->withJson($data);
		
	}
}
