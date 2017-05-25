<?php

namespace App\Services;

require(__DIR__."/../../flowAPI.php");


class PaymentService extends BaseService
{
    // Dummy
	public function getConfig($companyName) {
		//Get business config
		// $accountConfig = $this->container->ConfigService->getConfig($companyName);
		//Get global app config
        $flowAPI = new \flowAPI();
        print_r($flowAPI);
		$globalConfig = $this->container->ConfigService->getConfig();
		return $globalConfig;
	}
	
	public function createOrder($company,$orderData){
		$date = new DateTime();
		$timestamp = $date->format('YmdHis');

		//Get order info info
		extract($orderData);//$orderId, $amount, $concept, $payerEmail

		//Get app log path
		$logPath = $this->container->settings['flow']['logPath'];

		//Get global info
		$globalConfig = $this->container->ConfigService->getConfig();

		//Get business config info
		$businessConfig = $this->container->ConfigService->getConfig($company);

		// Setup config
		$flowConfig = [
			'flow_url_exito' => getenv('FLOW_URL_SUCCESS'),
			'flow_url_fracaso' => getenv('FLOW_URL_FAILED'),
			'flow_url_confirmacion' => getenv('FLOW_URL_CONFIRM'),
			'flow_url_retorno' => getenv('FLOW_URL_RETURN'),
			'flow_url_pago' => getenv('FLOW_ENV') === 'development' ? getenv('FLOW_URL_TEST') : getenv('FLOW_URL_PROD'),
			'flow_keys' => getenv('KEYS'),
			'flow_logPath' => $logPath,
			'flow_comercio' => getenv('EMAIL'),
			'flow_medioPago' => getenv('FLOW_MEDIUM '),
			'flow_tipo_integracion' => getenv('FLOW_INTEGRATION_TYPE')
		];		
		

		//Make request
        $flowAPI = new flowAPI($flowConfig);
		try {
			$flow_pack = $flowAPI->new_order($orderId, $amount, $concept, $payerEmail);
			// $flow_pack = $flowAPI->new_order($orden_compra, $monto, $concepto, $email_pagador);
			// Si desea enviar el medio de pago usar la siguiente línea
			//$flow_pack = $flowAPI->new_order($orden_compra, $monto, $concepto, $email_pagador, $medioPago);
			
			return $flow_pack;
		}
		catch (Exception $e) {
			throw new MyException('Failed creating flow order '. $e->getMessage());
		}
		
	}
}
