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
});