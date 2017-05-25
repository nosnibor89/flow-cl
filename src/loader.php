<?php

/**
*  Important Note: Here we have to use the namespace for the controllers in order to work.
*/


/**
 * Loades Class
 */
class Loader
{
    private $container;   
    function __construct($container)
    {
        $this->container = $container;
    }

    private static function loadController($controllerName, $methodName){
        // return new $controllerName:class();
    }
}
