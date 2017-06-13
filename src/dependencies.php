<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Vendor

// Can't use so far because constructor need the env filename which we are going to load dinamically
// $container['dotenv'] = function ($c) {
//      return new \Dotenv\Dotenv(__DIR__.'../config');
// };

//Services
$container['ConfigService'] = function ($c) {
    return new \App\Services\ConfigService($c);
};

$container['UtilService'] = function ($c) {
    return new \App\Services\UtilService($c);
};

$container['ValidationService'] = function ($c) {
    return new \App\Services\ValidationService($c);
};

$container['PaymentService'] = function ($c) {
    return new \App\Services\PaymentService($c);
};

//Controllers
$container['HomeController'] = function ($c) {
    return new \App\Controllers\HomeController($c);
};


$container['PaymentController'] = function ($c) {
    return new \App\Controllers\PaymentController($c);
};
