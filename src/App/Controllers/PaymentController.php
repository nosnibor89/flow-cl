<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Httpful\Request;
use App\Models\Order;
use App\Models\Operation;

class PaymentController extends BaseController
{
    /*
	*	Create a payment
	*/
    public function pay(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {

        $parsedBody = $request->getParsedBody(); // Get Data from request

        $company = $parsedBody['company'];
        $key = $parsedBody['apikey'];
        $order = new Order(
                        $parsedBody['order'],
                        $parsedBody['amount'],
                        $parsedBody['concept'],
                        $parsedBody['payer']
                    );

        //Validate company and apikey
        if (!$this->validationService->isApiKeyValid($company, $key)) {
            $data = [
                'error' => true,
                'message' => 'You don\'t have access to this resource'
            ];
            return $response->withJson($data, 401);
        }

        //Validate order data
        if (!$this->validationService->isOrderDataValid($order)) {
            $data = [
                'error' => true,
                'message' => 'Order values are incorrect'
            ];
            return $response->withJson($data, 400);
        }

        //Create order
        $payload = $this->paymentService->createOrder($company, $order);
        $url = $this->paymentService->getUrlFor(Operation::Payment);

        $data = [
            'payload' =>  $payload,
            'paymentUrl' => $url
        ];

        return $response->withJson($data, 200);
    }


    /*
	*	Confim transaction. This is a comunication beetwen the app and flow
	*/
    public function confirm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        //TODO: Implementation
        // $this->paymentService->confirmOrder();
    }

    /*
	*	Handle transaction failed
	*/
    public function failed(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $company = $args['company'];
        $orderData = $this->paymentService->getFlowOrderDetails($company, 'failed');
        $url = $this->utilService->assembleUrl($orderData);

        // Redirect to client site with transaction data
        return $response->withRedirect($url);
    }


    /*
	*	Handle transaction success
	*/
    public function success(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $orderData = $this->paymentService->getFlowOrderDetails($company, 'failed');
        $url = $this->utilService->assembleUrl($orderData);

        // Redirect to client site with transaction data
        return $response->withRedirect($url);
    }
}
