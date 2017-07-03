<?php
return [
    'settings' => [
        'displayErrorDetails' => getenv('APP_DEBUG'), // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        //Flow app config
        'flow' => [
            'configPath' => __DIR__.'/../config',
            'logPath' => __DIR__ . '/../logs',
            'certPath' => __DIR__.'/../cert',
            'flowUrlPayment' => '/'
        ],
        'redis' => [
           'scheme' => getenv('REDIS_PROTOCOL'),
           'host'   => getenv('REDIS_HOST'),
           'port'   => getenv('REDIS_PORT'),
        ]
    ],
];
