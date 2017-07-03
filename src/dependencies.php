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
$container['predis'] = function ($c) {
    $redisConfig = $c->get('settings')['redis'];
    return new Predis\Client($redisConfig);
};

//Services
$container['ConfigService'] = function ($c) {
    return new \App\Services\ConfigService($c);
};


// $container['ValidationService'] = function ($c) {
//     return new \App\Services\ValidationService($c);
// };

$container['PaymentService'] = function ($c) {
    return new \App\Services\PaymentService($c);
};

//Middlewares
$container['ValidateOrder'] = function ($c) {
    return new \App\Middlewares\ValidateOrder($c);
};

//Controllers
$container['HomeController'] = function ($c) {
    return new \App\Controllers\HomeController($c);
};


$container['PaymentController'] = function ($c) {
    return new \App\Controllers\PaymentController($c);
};

//Error Handlers
$container['notAllowedHandler'] = function ($c) {
     return ['\App\ErrorHandler','notAllowed'];
};

$container['phpErrorHandler'] = function ($c) {
    return ['\App\ErrorHandler','runTimeError'];
};

$container['notFoundHandler'] = function ($c) {
    return ['\App\ErrorHandler','notFound'];
};
