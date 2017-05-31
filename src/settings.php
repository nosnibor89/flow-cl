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
        /**
        * Test Site = http://flow.tuxpan.com/app/kpf/pago.php
        * Producction Site = https://www.flow.cl/app/kpf/pago.php
        */
        'flow' => [
            'configPath' => __DIR__.'/../config',
            'logPath' => __DIR__ . '/../logs',
            'certPath' => __DIR__.'/../cert',
            'flowUrlPayment' => '/'
        ]
    ],
];
