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

    $this->post('/success/{company}', '\App\Controllers\PaymentController:handleSuccessOrder');

    $this->post('/failed/{company}', '\App\Controllers\PaymentController:handleFailedOrder');

    $this->post('/transaction', '\App\Controllers\PaymentController:getTransactionDetails');
});
