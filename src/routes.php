<?php
// Routes


// App Home
$app->get('/', function ($request, $response, $args) {

    $data = array('API' => 'Payment-Gateway-Flow');
    return $response->withJson($data);
});

 $app->get('/testing', '\App\Controllers\PaymentController:test');


$app->get('/home', '\App\Controllers\PaymentController:test');

//API Group
$app->group('/api', function () {
    $this->post('/pay', '\App\Controllers\PaymentController:pay');

    // $this->post('/confirm', '\App\Controllers\PaymentController:confirm');

    $this->post('/success/{company}', '\App\Controllers\PaymentController:success');

    $this->post('/failed/{company}', '\App\Controllers\PaymentController:failed');

    $this->post('/validate', '\App\Controllers\PaymentController:validate');

   

});
