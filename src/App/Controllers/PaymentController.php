<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class PaymentController extends BaseController
{
	
	public function getConfig(ServerRequestInterface $request, ResponseInterface $response, $args) {
		// $request->
	}
	
	public function pay(ServerRequestInterface $request, ResponseInterface $response, $args) {

		$parsedBody = $request->getParsedBody(); // Get Data from request

		$orderData = [
			'orderId' => $parsedBody['order'],
			'amount' => $parsedBody['amount'],
			'concept' => $parsedBody['concept'],
			'payer' => $parsedBody['payer']
		];

		//Validate company and apikey
		if(!$this->validationService->isApiKeyValid($parsedBody['company'], $parsedBody['apikey'])){
			$data = [
				'error' => true,
				'message' => 'You don\'t have access to this resource'
			];
			return $response->withJson($data,401);
		}

		//Validate order data
		if(!$this->validationService->isOrderDataValid($orderData)){
			$data = [
				'error' => true,
				'message' => 'Order values are incorrect'
			];
			return $response->withJson($data,400);
		}

		//Create order

		return $response->withJson($data, 200);
		
	}


	public function failed(){

	}

	public function success(){

	}

}
