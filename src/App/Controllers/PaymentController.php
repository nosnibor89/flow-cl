<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class PaymentController extends BaseController
{
    /*
	*	Create a payment
	*/
    public function pay(ServerRequestInterface $request, ResponseInterface $response, $args)
    {

        $parsedBody = $request->getParsedBody(); // Get Data from request

        $company = $parsedBody['company'];
        $key = $parsedBody['apikey'];
        $orderData = [
            'orderId' => $parsedBody['order'],
            'amount' => $parsedBody['amount'],
            'concept' => $parsedBody['concept'],
            'payerEmail' => $parsedBody['payer']
        ];

        //Validate company and apikey
        if (!$this->validationService->isApiKeyValid($company, $key)) {
            $data = [
                'error' => true,
                'message' => 'You don\'t have access to this resource'
            ];
            return $response->withJson($data, 401);
        }

        //Validate order data
        if (!$this->validationService->isOrderDataValid($orderData)) {
            $data = [
                'error' => true,
                'message' => 'Order values are incorrect'
            ];
            return $response->withJson($data, 400);
        }

        //Create order
        $data = $this->paymentService->createOrder($company, $orderData);

        return $response->withJson($data, 200);
    }


    /*
	*	Confim transaction. This is a comunication beetwen the app and flow
	*/
    public function confirm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        //TODO: Maybe store the data in some place ????????????
        $this->paymentService->confirmOrder();
    }

    /*
	*	Handle transaction failed
	*/
    public function failed(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->paymentService->handleFailedOrder();
        // TODO: Find a way to let the user know something bad happened
    }


    /*
	*	Handle transaction success
	*/
    public function success(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->paymentService->handleSuccessOrder();
        // TODO: Find a way to let the user the payment was success
    }
}
