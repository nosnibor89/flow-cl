<?php

namespace App\Services;


class ValidationService extends BaseService
{
	public function isOrderValid($companyName, $key, $orderData) {
		$valid = false;
        $valid = isApiKeyValid($companyName, $key);
        $valid = isOrderDataValid($orderData);

        return $valid;

	}

    /*
    *   Validate APIKEY company
    */
    public function isApiKeyValid($companyName, $key){
        $this->container->ConfigService->loadConfig($companyName);

        $apikey = getenv('APIKEY') ? getenv('APIKEY') : getenv('APIKEY');

        return ($apikey && $apikey === $key ) ? true: false;
    }

    /*
    *   Validate New Order Data
    */
    public function isOrderDataValid($orderData){
        extract($orderData);
        if(empty($orderId) || empty($amount) || empty($concept) || empty($payer)){
            return false;
        }

        if(!is_numeric($amount)){
            return false;
        }

        if(!$this->isEmailValid($orderData['payer'])){
            return false;
        }

        return true;
    }

    /**
    *   Validate email format 
    */
    private function isEmailValid($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? true: false;
    }
	
}
