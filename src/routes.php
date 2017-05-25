<?php
// Routes


// App Home
$app->get('/', function ($request, $response, $args) {

    $data = array('API' => 'Payment-Gateway-Flow');
    return $response->withJson($data);

});


// $app->get('/home', '\App\Controllers\HomeController:home');

//API Group
$app->group('/api', function () {
    $this->post('/pay', '\App\Controllers\PaymentController:pay');

    $this->post('/confirm', '\App\Controllers\PaymentController:confirm');

    $this->post('/success', '\App\Controllers\PaymentController:success');

    $this->post('/failed', '\App\Controllers\PaymentController:failed');
});