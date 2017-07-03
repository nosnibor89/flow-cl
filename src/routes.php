<?php
// Routes


// App Home
$app->get('/', function ($request, $response, $args) {

    $data = array('API' => 'Flow-Payment-Gateway');
    return $response->withJson($data);
});

$app->get('/testing', function ($request, $response, $args) {

    die($request);
    $data = array('API' => 'Flow-Payment-Gateway');
    return $response->withJson($data);
})->add('\App\Middlewares\ValidateOrder');


//API Group
$app->group('/v1', function () {
    $this->post('/pay', '\App\Controllers\PaymentController:pay');

    $this->post('/success/{company}', '\App\Controllers\PaymentController:handleSuccessOrder');

    $this->post('/failed/{company}', '\App\Controllers\PaymentController:handleFailedOrder');

    $this->post('/transaction', '\App\Controllers\PaymentController:getTransactionDetails');
});
