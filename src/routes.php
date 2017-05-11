<?php
// Routes


// App Home
$app->get('/', function ($request, $response, $args) {

    $data = array('API' => 'Payment-Gateway-Flow');

    return $response->withJson($data);

});
