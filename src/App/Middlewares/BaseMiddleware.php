<?php

namespace App\Middlewares;

use \Interop\Container\ContainerInterface;

/**
*   Every controller extending this class have access shared resources
*/
abstract class BaseMiddleware
{
    protected $configService;
    
    public function __construct(ContainerInterface $container)
    {
        $this->configService = $container->get('ConfigService');
    }
}
