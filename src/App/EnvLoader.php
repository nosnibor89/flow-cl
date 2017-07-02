<?php

namespace App;

use \Dotenv\Exception\InvalidPathException;
use \RuntimeException;

/**
* Loads Env Variables
*/
class EnvLoader
{
    public static function setEnv(array $config)
    {
        $envFileDir =  __DIR__ . '/../../';
        $envFile = '.env';
      //What env to load ?
        if (file_exists($envFileDir.'.env.testing')) {
            //Load testing
            $envFile = '.env.testing';
        } elseif (file_exists($envFileDir.'.env')) {
            //Load local
            $envFile = '.env';
        } else {
            //load production
            foreach ($config as $key => $value) {
                putenv(sprintf('%s=%s', $key, $value));
            }
            return;
        }

      //Loading from files
        try {
            $dotenv = new \Dotenv\Dotenv($envFileDir, $envFile);
            $dotenv->load();
        } catch (InvalidPathException $e) {
            throw new RuntimeException(sprintf('Could not load environment correctly \n %s', $e->getMessage()));
        }
    }
}
