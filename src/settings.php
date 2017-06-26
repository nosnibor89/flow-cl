<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
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
           'scheme' => 'tcp',
           'host'   => 'redis',
           'port'   => 6379,
        ]
    ],
];
