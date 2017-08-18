<?php
// Routes


// App Home
$app->get('/', function ($request, $response, $args) {

    $data = array('API' => 'Flow-Payment-Gateway');
    return $response->withJson($data);
});

//API Group
$app->group('/v1', function () {
    $this->post('/pay', '\App\Controllers\PaymentController:pay')->add('\App\Middlewares\ValidateOrder');

    $this->post('/success/{company}/{medium}', '\App\Controllers\PaymentController:handleSuccessOrder');

    $this->post('/failed/{company}/{medium}', '\App\Controllers\PaymentController:handleFailedOrder');

    $this->post('/return/{company}/{medium}', '\App\Controllers\PaymentController:handleReturnOrder');

    $this->post('/transaction', '\App\Controllers\PaymentController:getTransactionDetails');
});
