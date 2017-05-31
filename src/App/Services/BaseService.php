<?php

namespace App\Services;

/**
*   Every service extending this class have access to the app container
*/
class BaseService
{
    protected $container;
    // protected $appConfig;
    
    public function __construct($container)
    {
        $this->container = $container;
    }
}
