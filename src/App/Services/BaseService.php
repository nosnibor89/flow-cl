<?php

namespace App\Services;

use \Interop\Container\ContainerInterface;

/**
*   Every service extending this class have access to the app container
*/
class BaseService
{
    protected $container;
    // protected $appConfig;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
