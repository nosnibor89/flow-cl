<?php

/**
*  Load feconfiguration variables according to enviroment
*/

$appConfig = [
    // App 
    'APP_DEBUG' => false,

    //Flow 
    'FLOW_URL' => 'https://www.flow.cl/app/kpf/pago.php',
    'FLOW_URL_SUCCESS' => 'https://flow.beelivery.cl/v1/success',
    'FLOW_URL_FAILED'=> 'https://flow.beelivery.cl/v1/failed',
    'FLOW_URL_CONFIRM'=> 'https://flow.beelivery.cl/v1/confirm',
    'FLOW_URL_RETURN'=> 'https://flow.beelivery.cl/v1',

    //Redis
    'REDIS_HOST'=> 'redis',
    'REDIS_PORT'=> 6379,
    'REDIS_PROTOCOL'=> 'tcp'
];

\App\EnvLoader::setEnv($appConfig);




