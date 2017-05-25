<?php
// Routes


// App Home
$app->get('/', function ($request, $response, $args) {

    $data = array('API' => 'Payment-Gateway-Flow');

    return $response->withJson($data);

});


$app->get('/home', '\App\Controllers\HomeController:home');

$app->get('/pay', '\App\Controllers\PaymentController:pay');