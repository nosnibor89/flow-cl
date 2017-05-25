<?php

namespace App\Services;

class ConfigService extends BaseService
{
    /**
    * Get data in configuration files (.env for global data)
    * @param    $companyName    string      Company name, if null it takes the .env global file
    */
	public function getConfig($companyName = "env") {
        $configPath = $this->container->settings['flow']['configPath'];
        echo $companyName;
		$dotenv = new \Dotenv\Dotenv($configPath, ".$companyName");
        $config = $dotenv->load();
        return $config;
	}
}
