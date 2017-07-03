<?php

namespace App\Services;

class ConfigService extends BaseService
{
    /**
    * Get data in configuration files (.env for global data)
    * @param    $companyName    string      Company name, if null it takes the .env global file
    */
    public function loadConfig($companyName = 'env')
    {
        $configPath = $this->container->settings['flow']['configPath'];
        $dotenv = new \Dotenv\Dotenv($configPath, sprintf('.%s', $companyName));
        $dotenv->load();
    }
}
