<?php

namespace App;

/**
* Loads Env Variables
*/
class EnvLoader
{
   public static function setEnv(array $config){

    //What env to load
    if(file_exists(__DIR__ . '/../../.env')){
        //Load local
        $dotenv = new \Dotenv\Dotenv(__DIR__ . '/../../.env');
        $dotenv->load();

    }elseif(file_exists(__DIR__ . '/../../.env.testing')){
        //load testing
        $dotenv = new \Dotenv\Dotenv(__DIR__ . '/../../.env.testing');
        $dotenv->load();
    }else{
        //load production
        foreach ($config as $key => $value) {
            putenv(sprintf('%s=%s', $key, $value));
        }
    }


    try{
        
    }catch(InvalidPathException $e){
        throw new Error(sprintf('Could not load environment correctly \n %s', $e->getMessage()));
    }

    echo getenv('FLOW_URL');
    die();

}
}
